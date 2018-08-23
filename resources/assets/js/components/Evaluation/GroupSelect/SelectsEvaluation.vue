<template>
    <div class="row">

        <!-- Form to Teachers -->
        <template>
            <!-- Grado -->
            <div class="col-md-2">
                <label for="">Grado</label>
                <select @change="changeSelects('grade')" class="form-control" v-model="grade_selected.id">
                    <option :value="null">Seleccionar</option>
                    <option v-for="grade in propsData.grades" :value="grade.id">
                        {{ grade.name }}
                    </option>
                </select>
            </div>
            <!-- Grupos -->
            <div class="col-md-2">
                <label for="">Grupo</label>
                <select @change="changeSelects('group')" class="form-control" v-model="group_selected.id">
                    <option :value="null">Seleccionar</option>
                    <option v-for="group in grade_selected.groups" :value="group.group_id">
                        {{ group.group_name }}
                    </option>
                </select>
            </div>
            <!-- Areas -->
            <div class="col-md-3">
                <label for="">Areas</label>
                <select @change="changeSelects('area')" class="form-control" v-model="area_selected.id">
                    <option :value="null">Seleccionar</option>
                    <option v-for="area in group_selected.areas" :value="area.area_id">
                        {{ area.area_name }}
                    </option>
                </select>
            </div>
            <!-- Asignaturas -->
            <div class="col-md-3">
                <label for="">Asignaturas</label>
                <select @change="changeSelects('asignature')" class="form-control" v-model="asignature_selected.id">
                    <option :value="null">Seleccionar</option>
                    <option v-for="asignature in area_selected.asignatures" :value="asignature.asignature_id">
                        {{ asignature.asignature_name }}
                    </option>
                </select>
            </div>
            <!-- Periods -->
            <div class="col-md-2">
                <label for="">Periodo</label>
                <select @change="changeSelects('period')" class="form-control" v-model="period_selected.id">
                    <option :value="null">Seleccionar</option>
                    <option v-for="period in group_selected.periods" :value="period.periods_id">
                        {{ period.periods_name }}
                    </option>
                </select>
            </div>
            <div class="col-md-12">
                <div v-if="period_selected.info" style="text-align: center; margin-top: 4px;">
                    <small>
                        <strong>Inicia:</strong>
                        {{ period_selected.info.start_date | moment("dddd, MMMM Do YYYY, h:mm:ss a") }}
                    </small>
                    |
                    <small>
                        <strong>Finaliza:</strong>
                        {{period_selected.info.end_date | moment("dddd, MMMM Do YYYY, h:mm:ss a")}}
                    </small>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {

        name: "selects-evaluation",
        props: ['props-data'],
        data() {
            return {
                grade_selected: {
                    id: null,
                    groups: []
                },
                group_selected: {
                    id: null,
                    areas: [],
                    periods: [],
                    working_day_id: null,
                    info: null,
                },
                area_selected: {
                    id: null,
                    area_name: null,
                    asignatures: [],
                    info: null,
                },
                asignature_selected: {
                    id: null,
                    info: null,
                },
                period_selected: {
                    id: null,
                    info: null
                }
            }
        },
        created() {
            this.init()
            this.initToast()
        },
        watch: {

        },
        methods: {
            init() {

                this.grade_selected.id = this.propsData.grade_selected.id
                this.changeSelects('grade')
                this.group_selected.id = this.propsData.group_selected.id
                this.group_selected.working_day_id = this.propsData.group_selected.working_day_id
                this.changeSelects('group')

                if (this.propsData.teacher_selected != null) {
                    this.area_selected.id = this.propsData.area_selected.id
                } else {
                    this.area_selected.id = null
                }
                this.changeSelects('area')

                if (this.propsData.teacher_selected != null) {
                    this.asignature_selected.id = this.propsData.asignature_selected.id
                    this.selectedAsignature('asignature')
                } else {
                    this.asignature_selected.id = null
                }
            },

            initToast() {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            },

            changeSelects(param) {
                this.selectedGrade(param)
                this.selectedGroup(param)
                this.selectedArea(param)
                this.selectedAsignature(param)
                this.selectedPeriod(param)

                this.emitEventAllSelected()
            },
            selectedGrade(param) {
                if (param == 'grade') {
                    if (this.grade_selected.id != null) {
                        //Busca en el arreglo de grados, el grado que fue seleccionado
                        let grade_selected = this.propsData.grades.find((grade) => {
                            return grade.id == this.grade_selected.id
                        })

                        this.grade_selected.groups = grade_selected.vectorGroups


                        if (this.propsData.grades.length > 1) {
                            this.group_selected.id = null
                            this.group_selected.working_day_id = null
                        }

                        if (this.grade_selected.groups.length == 1) {
                            this.group_selected.id = this.grade_selected.groups[0].group_id
                            this.group_selected.working_day_id = this.grade_selected.groups[0].working_day_id
                        }

                        this.selectedGroup('group')

                    } else {
                        this.grade_selected.groups = null
                        this.group_selected.id = null
                        this.group_selected.areas = null
                        this.group_selected.periods = null
                        this.period_selected.id = null
                        this.area_selected.id = null
                        this.area_selected.asignatures = null
                        this.asignature_selected.id = null

                    }
                }
            },

            selectedGroup(param) {
                if (param == 'group') {

                    if (this.group_selected.id != null) {
                        let group_selected = this.grade_selected.groups.find((group) => {
                            return group.group_id == this.group_selected.id
                        })
                        this.group_selected.areas = group_selected.vectorAreas
                        this.group_selected.working_day_id = group_selected.working_day_id

                        this.if_parent_is_older_than_one(
                            {first: 'grade_selected', second: 'groups'},
                            {first: 'area_selected'},)

                        this.if_childs_is_same_than_one(
                            {first: 'group_selected', second: 'areas', third: 'area_id'},
                            {first: 'area_selected'},)

                        this.getPeriodsByWorkingDay()
                        this.selectedArea('area')
                        this.selectedPeriod('period')

                    } else {
                        this.group_selected.areas = null
                        this.area_selected.id = null
                        this.group_selected.periods = null
                        this.period_selected.id = null
                        this.group_selected.working_day_id = null
                        this.area_selected.asignatures = null
                        this.asignature_selected.id = null
                    }
                }
            },
            selectedArea(param) {
                if (param == 'area') {
                    if (this.area_selected.id != null) {
                        let area = this.group_selected.areas.find((area) => {
                            return area.area_id == this.area_selected.id
                        })
                        this.area_selected.asignatures = area.vectorAsignatures

                        this.if_parent_is_older_than_one(
                            {first: 'group_selected', second: 'areas'},
                            {first: 'asignature_selected'},)
                        this.if_childs_is_same_than_one(
                            {first: 'area_selected', second: 'asignatures', third: 'asignature_id'},
                            {first: 'asignature_selected'},)
                        this.if_childs_is_older_than_one(
                            {first: 'area_selected', second: 'asignatures'},
                            {first: 'asignature_selected'},)

                        this.selectedAsignature('asignature')

                    } else {
                        this.if_parent_id_is_null(
                            {first: 'area_selected', second: 'asignatures'},
                            {first: 'asignature_selected'},)
                    }
                }
            },
            selectedAsignature(param) {
                if (param == 'asignature') {
                    if (this.asignature_selected.id != null) {
                        let asignature = this.area_selected.asignatures.find((asignature) => {
                            return asignature.asignature_id == this.asignature_selected.id
                        })
                        this.asignature_selected.info = asignature

                    }
                }
            },
            selectedPeriod(param) {
                if (param == 'period') {
                    if (this.period_selected.id != null) {
                        let period = this.group_selected.periods.find((period) => {
                            return period.periods_id == this.period_selected.id
                        })
                        this.period_selected.info = period
                    }
                }
            },

            emitEventAllSelected() {
                if (this.grade_selected.id != null &&
                    this.group_selected.id != null &&
                    this.area_selected.id != null &&
                    this.asignature_selected.id != null &&
                    this.period_selected.id != null) {
                    let data = {
                        grade_selected: this.grade_selected,
                        group_selected: this.group_selected,
                        area_selected: this.area_selected,
                        asignature_selected: this.asignature_selected,
                        period_selected: this.period_selected
                    }
                    this.$bus.$emit("eventElementsSelected@SelectsEvaluation", data)
                }
            },
            getPeriodsByWorkingDay() {
                this.period_selected.id = null
                axios.get('/ajax/getPeriodsByWorkingDay/' + this.group_selected.working_day_id).then(res => {
                    this.group_selected.periods = res.data
                })
            },

            if_parent_is_older_than_one(parent, child) {
                if (this[parent.first][parent.second].length > 1)
                    this[child.first].id = null
            },
            if_childs_is_same_than_one(parent, child) {
                if (this[parent.first][parent.second].length == 1)
                    this[child.first].id = this[parent.first][parent.second][0][parent.third]
            },
            if_childs_is_older_than_one(parent, child) {
                if (this[parent.first][parent.second].length > 1)
                    this[child.first].id = null
            },
            if_parent_id_is_null(parent, child) {
                this[parent.first][parent.second] = null
                this[child.first].id = null
            },

        }
    }
</script>

<style scoped>

</style>