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
                <option v-for="n in 4" :value="n">
                    {{ n }}
                </option>
            </select>
        </div>
        <div class="col-md-12">

        </div>

    </div>
</template>

<script>

    import {mapState, mapMutations, mapGetters} from 'vuex'
    import RowEvaluation from './EvaluationPeriods/RowEvaluation';

    export default {
        name: "evaluation-manager",
        props: {
            enrollments: {type: Array},
            group: {type: Object},
            asignatureid: {type: Number},
            collectionnotes: {type: Array}
        },
        components: {
            RowEvaluation
        },
        data() {
            return {
                periodid: 0,
            }
        },
        created() {
            this.getParameters()
            this.getAsignatureId(this.asignatureid)
        },
        computed: {
            ...mapState([
                'asignature',
            ]),

        },
        methods: {
            getParameters() {
                this.$store.dispatch('parameters')
            },
            getAsignatureId(asignatureid){
                this.$store.dispatch('asignatureById',{
                    asignatureid:this.asignatureid
                })

            },
            getEvaluationsByPeriod(){

            }
        }

    }
</script>

<style scoped>

</style>