<template>
    <div class="row">

        <div class="col-md-6">
            <h5>{{asignature.name}}</h5>
            <h5>{{group.name}}</h5>


            <span style="color:red;font-weight: bold ">{{isConexion?'':'Usted no tiene conexión a internet'}}</span>
        </div>
        <div class="col-md-3">
            <div v-if="periodSelected">
                <performances-manager></performances-manager>
            </div>
        </div>
        <div class="col-md-2">
            <label for="">Seleccionar Periodo</label>
            <select v-on:change="getEvaluationsByPeriod" class="form-control" name="" v-model="periodid">
                <option :value="0">Seleccionar</option>
                <option v-for="period in periodsworkingday" :value="period.periods_id">
                    {{ period.periods_name }}
                </option>
            </select>
        </div>
        <div class="col-md-12">
            <div v-if="isCollection">
                <table-evaluation></table-evaluation>
            </div>
        </div>

    </div>
</template>

<script>

    import {mapState} from 'vuex'
    import RowEvaluation from './evaluation-periods/RowEvaluation';
    import TableEvaluation from './evaluation-periods/TableEvaluation';
    import PerformancesManager from './evaluation-periods/performances/PerformancesManager';

    export default {
        name: "evaluation-manager",
        props: {
            group: {type: Object},
            asignatureid: {type: Number},
            filter: {type: String}
        },
        components: {
            RowEvaluation, TableEvaluation, PerformancesManager
        },
        data() {
            return {
                periodid: 0,
                state: false,
            }
        },
        created() {
            this.$store.state.isTypeGroup = (this.filter == "group" || this.filter == "basic") ? true : false

            this.getAsignatureById()
            this.getConfigInstitution()
            this.getGradeById()
            this.getGrades()
            this.getParameters()
            this.getGroupPensum()
            this.getPeriodsByWorkingDay();
            this.getInstitutionOfTeacher()


        },

        updated() {
            this.getConexion()
        },
        computed: {
            ...mapState([
                'grade',
                'asignature',
                'periodSelected',
                'institutionOfTeacher',
                'periodsworkingday',
                'isCollection',
                'isConexion',
                'groupPensum',
                'isTypeGroup',
                'configInstitution'
            ]),

        },
        methods: {

            getConfigInstitution() {
                this.$store.dispatch('configInstitution', {group_type: this.filter})
            },
            getParameters() {
                this.$store.dispatch('parameters', {group_type: this.filter})
            },
            getAsignatureById() {
                this.$store.dispatch('asignatureById', {
                    asignatureid: this.asignatureid,
                    grade_id: this.group.grade_id,
                    isGroup: this.$store.state.isTypeGroup
                })
            },
            getGroupPensum() {
                this.$store.dispatch('groupPensum', {
                    group_id: this.group.id,
                    asignatures_id: this.asignatureid,
                    school_year_id: 1,
                    isGroup: this.$store.state.isTypeGroup
                })
            },
            getGradeById() {
                this.$store.dispatch('gradeById', {
                    grade_id: this.group.grade_id,
                    isGroup: this.$store.state.isTypeGroup
                })
            },
            getEvaluationsByPeriod() {
                this.$store.state.isCollection = false
                this.$store.state.periodSelected = this.periodid

                this.getCollectionNotes()
            },
            getPeriodsByWorkingDay() {
                this.$store.dispatch('periodsByWorkingDay', {
                    workingdayid: this.group.working_day_id || this.group.section_id,
                    isGroup: this.$store.state.isTypeGroup
                })
            },

            getCollectionNotes() {
                this.$store.dispatch('collectionNotes', {
                    groupid: this.group.id,
                    asignatureid: this.asignatureid,
                    periodid: this.$store.state.periodSelected,
                    isGroup: this.$store.state.isTypeGroup
                })
            },
            getCollectionNotesFinal(){
                this.$store.dispatch('collectionNotesFinal', {
                    groupid: this.group.id,
                    asignatureid: this.asignatureid,
                    periodid: this.$store.state.periodSelected,
                    isGroup: this.$store.state.isTypeGroup
                })
            },
            getGrades() {
                this.$store.dispatch('grades', {
                    isGroup: this.$store.state.isTypeGroup
                })
            },
            getInstitutionOfTeacher() {
                this.$store.dispatch('institutionOfTeacher', {
                    isGroup: this.$store.state.isTypeGroup
                })
            },

            getConexion() {
                this.$store.dispatch('verifyConexion', {
                    isGroup: this.$store.state.isTypeGroup
                })
            }

        }

    }
</script>

<style>
    .modal-content {
        border-radius: 0px;
    }

    .modal-dialog {
        width: 88%;
        margin: 30px auto;
    }

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        padding: 3px;
        padding-top: 3px;
        padding-right: 3px;
        padding-left: 3px;
        line-height: 1.42857143;
        vertical-align: middle !important;
        border-top: 1px solid #ddd;
    }

</style>