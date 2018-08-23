<template>
    <div class="row">

        <!-- Grado -->
        <div class="col-md-3">
            <label for="">Grado</label>
            <select @change="changeSelects('grade')" class="form-control" v-model="grade_selected.id">
                <option :value="null">Seleccionar</option>
                <option v-for="grade in grades" :value="grade.id">
                    {{ grade.name }}
                </option>
            </select>
        </div>

        <!-- Areas -->
        <div class="col-md-3">
            <label for="">Areas</label>
            <select @change="changeSelects('area')" class="form-control" v-model="area_selected.id">
                <option :value="null">Seleccionar</option>
                <option v-for="area in mainComponentObject.areas" :value="area.id">
                    {{ area.name }}
                </option>
            </select>
        </div>

        <!-- Asignaturas -->
        <div class="col-md-3">
            <label for="">Asignaturas</label>
            <select @change="changeSelects('asignature')" class="form-control" v-model="asignature_selected.id">
                <option :value="null">Seleccionar</option>
                <option v-for="asignature in mainComponentObject.asignatures" :value="asignature.asignatures_id">
                    {{ asignature.name }}
                </option>
            </select>
        </div>
        <!-- Parametros -->
        <div class="col-md-2">
            <label for="">Parámetro</label>
            <select @change="changeSelects('parameter')" class="form-control" v-model="parameter_selected.id">
                <option :value="null">Seleccionar</option>
                <option v-for="(parameter,index) in stateEvaluation.parameters_selected" :value="parameter.id">
                    {{ parameter.parameter }}
                </option>
            </select>
        </div>
        <!-- Periods -->
        <div class="col-md-1">
            <label for="">Periodo</label>
            <select @change="changeSelects('period')" class="form-control" v-model="period_selected.id">
                <option :value="null">Seleccionar</option>
                <option v-for="period in mainComponentObject.periods" :value="period">
                    {{ period }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "selects-performances",
        computed: {
            ...mapState([
                'grades',
                'stateEvaluation'
            ]),
        },
        data() {
            return {
                mainComponentObject: {
                    areas: [],
                    asignatures: [],
                    periods: [1, 2, 3, 4],
                },
                grade_selected: {
                    id: null,
                },
                area_selected: {
                    id: null,
                },
                asignature_selected: {
                    id: null,
                    pensum_id: null
                },
                period_selected: {
                    id: null
                },
                parameter_selected: {
                    id: null
                },
                state: false,
                index: 0,
            }
        },
        created() {

            this.$bus.$on('eventReadyDataState@EvaluationXManager', dd => {

                if (this.index == 0 || this.grade_selected.id != this.$store.state.stateEvaluation.grade_selected.id) {
                    this.grade_selected.id = this.$store.state.stateEvaluation.grade_selected.id
                    this.getAreasByGrade()
                    this.area_selected.id = this.$store.state.stateEvaluation.area_selected.id
                    this.getAsignaturesByAreaPensum()
                    this.asignature_selected.id = this.$store.state.stateEvaluation.asignature_selected.id
                    this.period_selected.id = this.$store.state.stateEvaluation.period_selected.id
                    this.parameter_selected.id = this.$store.state.stateEvaluation.parameter_selected_id
                    this.emitEventAllSelected()
                }

            })


        },
        destroyed(){
            this.$bus.$off('eventReadyDataState@EvaluationXManager')
        },
        methods: {

            changeSelects(param) {

                this.selectedGrade(param)
                this.selectedArea(param)
                this.selectedAsignature(param)

                this.emitEventAllSelected()

            },

            selectedGrade(param) {
                if (param == 'grade') {
                    if (this.grade_selected.id != null) {
                        this.getAreasByGrade()
                    } else {
                        this.area_selected.id = null
                        this.mainComponentObject.areas = null
                    }
                }
            },

            selectedArea(param) {
                if (param == 'area') {
                    if (this.area_selected.id != null) {
                        this.getAsignaturesByAreaPensum()
                    } else {
                        this.asignature_selected.id = null
                        this.mainComponentObject.asignatures = null
                    }
                }
            },
            selectedAsignature(param) {
                if (param == 'asignature') {
                    if (this.asignature_selected.id != null) {
                        let asignature_selected = this.mainComponentObject.asignatures.find((asignature) => {
                            return asignature.asignatures_id == this.asignature_selected.id
                        })
                        this.asignature_selected.pensum_id = asignature_selected.pensum_id
                    } else {
                        this.asignature_selected.pensum_id = null

                    }
                }
            },

            emitEventAllSelected() {
                if (this.grade_selected.id != null &&
                    this.area_selected.id != null &&
                    this.asignature_selected.id != null &&
                    this.parameter_selected.id != null &&
                    this.period_selected.id != null) {

                    let data = {
                        period_selected: this.period_selected,
                        parameter_selected: this.parameter_selected,
                        asignature_selected: this.asignature_selected,
                    }
                    this.$bus.$emit("eventElementsSelected@SelectsPerformances", data)

                }
            },

            getAreasByGrade() {
                if (this.grade_selected.id) {

                    let params = {
                        grade_id: this.grade_selected.id,
                    }
                    axios.get('/ajax/getAreasByGrade', {params}).then(res => {
                        this.mainComponentObject.areas = res.data

                        //Se ejecuta en la segunda llamada a este metódo
                        if (this.index > 0) {
                            if (this.mainComponentObject.areas.length > 1)
                                this.area_selected.id = null

                            if (this.mainComponentObject.areas.length == 1)
                                this.area_selected.id = this.mainComponentObject.areas[0].id

                            this.selectedArea('area')
                        }
                        //Se incrementa dicha variable para identificar la primera vez que
                        //se ejecuta varias funciones en create()
                        this.index++
                    })
                }
            },


            getAsignaturesByAreaPensum() {
                let params = {
                    grade_id: this.grade_selected.id,
                    area_id: this.area_selected.id,
                }
                axios.get('/ajax/getAsignaturesByAreaPensum', {params}).then(res => {
                    this.mainComponentObject.asignatures = res.data
                    //Se ejecuta en la segunda llamada a este metódo
                    if (this.index > 0) {
                        if (this.mainComponentObject.asignatures.length == 1) {
                            this.asignature_selected.id = this.mainComponentObject.asignatures[0].asignatures_id
                            this.asignature_selected.pensum_id = this.mainComponentObject.asignatures[0].pensum_id
                        }

                        if (this.mainComponentObject.asignatures.length > 1) {
                            this.asignature_selected.id = null
                            this.asignature_selected.pensum_id = null
                        }
                    }
                    this.selectedAsignature('asignature')
                })
            },
        }
    }
</script>

<style scoped>

</style>