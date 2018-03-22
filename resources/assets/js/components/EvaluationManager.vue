<template>
    <div class="row">

        <div class="col-md-6">
            <h5>{{asignature.name}}</h5>
            <h5>{{group.name}}</h5>
        </div>
        <div class="col-md-3">
            <div class="form-group" style="padding-top:22px;">
                <button class="btn btn-default" style="margin:0px auto; display: block">Configurar Desempeños</button>
            </div>
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

    export default {
        name: "evaluation-manager",
        props: {
            group: {type: Object},
            asignatureid: {type: Number},
        },
        components: {
            RowEvaluation, TableEvaluation
        },
        data() {
            return {
                periodid:0,
                state: false
            }
        },
        created() {
            this.getParameters()
            this.getAsignatureId(this.asignatureid)
            this.getPeriodsByWorkingDay(this.group.working_day_id);
        },
        computed: {
            ...mapState([
                'asignature',
                'periodsworkingday',
                'periodSelected',
                'isCollection'
            ]),

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
                //console.log(this.$store.state.isCollection)
                this.getCollectionNotes(this.group.id, this.asignatureid, this.$store.state.periodSelected)


            },
            getPeriodsByWorkingDay(workingdayid) {
                this.$store.dispatch('periodsByWorkingDay', {
                    workingdayid: workingdayid
                })
            },

            getCollectionNotes(groupid, asignatureid, periodid){
                this.$store.dispatch('collectionNotes', {
                    groupid: groupid,
                    asignatureid: asignatureid,
                    periodid: periodid
                })
            }

        }

    }
</script>

<style scoped>

</style>