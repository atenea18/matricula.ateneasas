<template>
    <div class="row">
        <div class="col-md-3">
            <div v-if="grade">
                <label>Seleccionar Grado</label>
                <select v-on:change="eventChange('grade')" class="form-control" name="" v-model="objectSelectGrade.id">
                    <option :value="0">Seleccionar</option>
                    <template v-for="row in  grades">
                        <option :value="row.id">
                            {{ row.name }}
                        </option>
                    </template>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div v-if="grade">
                <label>Seleccionar Área</label>
                <select v-on:change="eventChange('area')" class="form-control" name=""
                        v-model="objectSelectAreas.id">
                    <option :value="0">Seleccionar</option>
                    <template v-for="row in areas">
                        <option :value="row.id">
                            {{ row.name }}
                        </option>
                    </template>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div v-if="grade">
                <label>Seleccionar Asignatura</label>
                <select v-on:change="eventChange('asignature')" class="form-control" name=""
                        v-model="objectSelectAsignature.id">
                    <option :value="0">Seleccionar</option>
                    <template v-for="row in asignatures">
                        <option :value="row.id">
                            {{ row.name }}
                        </option>
                    </template>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <label>Parametro</label>
            <select v-on:change="eventChange('parameter')" class="form-control" name=""
                    v-model="objectSelectParameter.id">
                <option :value="0">Seleccionar</option>
                <template v-for="row in parameters">

                    <option v-if="row.evaluation_type_id==1" :value="row.id">
                        {{ row.parameter }}
                    </option>
                </template>
            </select>
        </div>
        <div class="col-md-1">
            <label for="">Periodo</label>
            <select v-on:change="eventChange('period')" class="form-control" name="" v-model="objectSelectPeriod.id">
                <option :value="0">Seleccionar</option>
                <option v-for="period in periodsworkingday" :value="period.periods_id">
                    {{ period.periods_name }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import AtSelectObject from '../../partials/AtSelectObject'

    export default {
        name: "row-selects",
        components: {
            AtSelectObject
        },

        data() {
            return {
                objectSelectGrade: {
                    id: 0
                },
                objectSelectAsignature: {
                    id: 0
                },
                objectSelectAreas: {
                    id: 0
                },
                objectSelectPeriod: {
                    id: 0
                },
                objectSelectParameter: {
                    id: 0
                },
                objectRowSelect: {},
                asignatures: [],
                areas: [],
                pensum_id: 0
            }
        },

        created() {

            this.objectSelectGrade.id = this.$store.state.grade.id
            this.objectSelectAsignature.id = this.$store.state.asignature.id
            this.objectSelectAreas.id = this.$store.state.asignature.areas_id
            this.objectSelectPeriod.id = this.$store.state.periodSelected
            this.objectSelectParameter.id = this.$store.state.parameters[0].id


        },
        mounted() {
            this.getAreas()
            this.getAsignatures()
        },

        computed: {
            ...mapState([
                'grade',
                'grades',
                'asignature',
                'periodsworkingday',
                'periodSelected',
                'institutionOfTeacher',
                'parameters',
                'isTypeGroup'
            ]),


        },
        methods: {

            eventChange(param) {
                if (param == 'grade') {
                    this.objectSelectAreas.id = 0
                    this.objectSelectAsignature.id = 0
                    this.getAreas()
                    this.getAsignatures()
                }
                if (param == 'area') {
                    this.objectSelectAsignature.id = 0
                    this.getAreas()
                    this.getAsignatures()
                }
                if (param == 'asignature' || param == 'parameter' || param == 'period') {
                    if (this.objectSelectPeriod.id != 0 && this.objectSelectParameter.id != 0 && this.objectSelectAsignature.id != 0) {

                        this.getPensumId(this.objectSelectAsignature.id)
                        this.emitRowSelectsData()

                    } else {

                    }
                }


            },

            getAsignatures() {
                let params = {
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    grade_id: this.objectSelectGrade.id,
                    areas_id: this.objectSelectAreas.id,
                    isGroup: this.$store.state.isTypeGroup
                }
                axios.get('/ajax/asignaturesByGrade', {params}).then(res => {
                    this.asignatures = res.data;
                    if (this.pensum_id == 0) {
                        this.getPensumId(this.objectSelectAsignature.id)
                        this.emitRowSelectsData()
                    }
                })

            },
            getAreas() {
                let params = {
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    grade_id: this.objectSelectGrade.id,
                    isGroup: this.$store.state.isTypeGroup

                }
                axios.get('/ajax/areasByGrade', {params}).then(res => {
                    this.areas = res.data;
                })
            },

            getPensumId(asignature_id) {

                this.asignatures.forEach(asignature => {
                    if (asignature.id == asignature_id) {
                        this.pensum_id = asignature.pensum_id
                    }
                })
            },
            emitRowSelectsData() {
                let performance = {
                    pensum_id: this.pensum_id,
                    periods_id: this.objectSelectPeriod.id,
                    evaluation_parameters_id: this.objectSelectParameter.id
                }
                if(this.pensum_id != 0){
                    this.$bus.$emit("get-param-of-row-selects", performance);
                }

            }
        }
    }
</script>

<style scoped>

</style>