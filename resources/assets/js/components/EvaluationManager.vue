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
            <select v-on:change="" class="form-control" name="">
                <option :value="0">Seleccionar</option>
                <option v-for="n in 4" :value="n">
                    {{ n }}
                </option>
            </select>
        </div>
        <div class="col-md-12">


            <table class="table table-bordered">
                <thead>
                <tr style="font-size: 11px">
                    <th scope="col">#</th>
                    <th>
                        Nombres Y Apellidos
                    </th>
                    <th>
                        A
                    </th>
                    <template v-for="para in parameters">
                        <th :colspan="para.notes_parameter.length+1">
                            {{para.parameter}}
                        </th>
                    </template>
                    <th>
                        Nota Final
                    </th>
                </tr>
                </thead>
                <tbody>
                <template style="font-size: 11px" v-for="(enrollment, index) in notes">
                    <row-evaluation
                            :setting="{index:index, asignatureid:asignature.id, periodid:periodid, enrollment:enrollment}"></row-evaluation>
                </template>

                </tbody>
            </table>
        </div>

    </div>
</template>

<script>
    import {mapState, mapMutations, mapGetters} from 'vuex';
    import RowEvaluation from './EvaluationPeriods/RowEvaluation';

    export default {
        name: "evaluation-manager",
        props: {
            enrollments: {type: Array},
            group: {type: Object},
            asignature: {type: Object},
            notes: {type: Array}
        },
        components: {
            RowEvaluation
        },
        computed: {
            ...mapState([
                'parameters'
            ]),
        }
        ,
        data() {
            return {
                periodid: 0,
            }
        },
        created() {
            this.getParameters()

        },
        methods: {
            getParameters() {
                this.$store.dispatch('parameters')
            }
        }

    }
</script>

<style scoped>

</style>