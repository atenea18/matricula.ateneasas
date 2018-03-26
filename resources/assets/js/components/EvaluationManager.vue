<template>
    <div class="row">

        <div class="col-md-6">
            <h5>{{asignature.name}}</h5>
            <h5>{{group.name}}</h5>
        </div>
        <div class="col-md-3">
           <performances-manager :objectPerformances="params"></performances-manager>
        </div>
        <div class="col-md-3">
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

    import {mapState, mapMutations, mapGetters} from 'vuex'
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

            this.getParameters()
            this.getAsignatureId(this.asignatureid)
            this.getPeriodsByWorkingDay(this.group.working_day_id);
            this.getInstitutionOfTeacher()
            this.getGrades()

        },
        computed: {
            ...mapState([
                'grades',
                'asignature',
                'periodsworkingday',
                'periodSelected',
                'isCollection',
                'institutionOfTeacher'
            ]),
            params() {
                return {
                    grade_id: this.group.grade_id,
                    asignature_id: this.asignatureid,
                    period_id: this.$store.state.periodSelected
                }
            }

        },
        methods: {
            getParameters() {
                this.$store.dispatch('parameters')
            },
            getAsignatureId(asignatureid) {
                this.$store.dispatch('asignatureById', {
                    asignatureid: this.asignatureid
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

        }

    }
</script>

<style>
    .modal-content {
        border-radius: 0px;
    }

    .modal-dialog {
        width: 80%;
        margin: 30px auto;
    }

    .form-control {
        border-radius: 0px;
    }


</style>