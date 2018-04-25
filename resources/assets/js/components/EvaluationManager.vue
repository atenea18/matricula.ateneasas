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
            this.getGradeById(this.group.grade_id)
            this.getAsignatureById(this.asignatureid, this.group.grade_id)
            this.getParameters()
            this.getGrades()
            this.getGroupPensum(this.group.id, this.asignatureid, 1)
            this.getPeriodsByWorkingDay(this.group.working_day_id);
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
                'groupPensum'
            ]),

        },
        methods: {
            getParameters() {
                this.$store.dispatch('parameters', {group_type:"subgroup"})
            },
            getAsignatureById(asignatureid, grade_id) {
                this.$store.dispatch('asignatureById', {
                    asignatureid: asignatureid,
                    grade_id: grade_id
                })
            },
            getGroupPensum(group_id, asignatures_id, school_year_id) {
                this.$store.dispatch('groupPensum', {
                    group_id: group_id,
                    asignatures_id: asignatures_id,
                    school_year_id: school_year_id
                })
            },
            getGradeById(grade_id) {
                this.$store.dispatch('gradeById', {
                    grade_id: grade_id
                })
            },
            getEvaluationsByPeriod() {
                this.$store.state.isCollection = false
                this.$store.state.periodSelected = this.periodid
                this.getCollectionNotes(this.group.id, this.asignatureid, this.$store.state.periodSelected)
            },
            getPeriodsByWorkingDay(workingdayid) {
                this.$store.dispatch('periodsByWorkingDay', {
                    workingdayid: workingdayid
                })
            },

            getCollectionNotes(groupid, asignatureid, periodid) {
                this.$store.dispatch('collectionNotes', {
                    groupid: groupid,
                    asignatureid: asignatureid,
                    periodid: periodid
                })
            },
            getGrades() {
                this.$store.dispatch('grades')
            },
            getInstitutionOfTeacher() {
                this.$store.dispatch('institutionOfTeacher')
            },

            getConexion() {
                this.$store.dispatch('verifyConexion')
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