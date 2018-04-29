<template>
    <table class="table table-bordered">

        <!-- Fila de titulos -->
        <thead>
        <tr style="font-size: 11px">
            <th scope="col" rowspan="2">No.</th>
            <th rowspan="2"> NOMBRES Y APELLIDOS</th>
            <th rowspan="2"> FAA</th>
            <template v-for="para in parameters">
                <th :colspan="para.notes_parameter.length+1" style="text-align: center">
                    {{para.parameter}}
                </th>
            </template>
            <th rowspan="2"> VAL </th>
        </tr>
        <tr>
            <!--
            <th></th>
            <th></th>
            <th></th>
            -->
            <template v-for="para in parameters">
                <template v-for="note in para.notes_parameter">
                    <relation-performances :objectInput="note" :ref="'parameter'+para.id"></relation-performances>
                </template>
                <th></th>
            </template>
            <!--
            <th> Nota Final </th>
            -->
        </tr>
        </thead>
        <!-- Fin fila de titulos -->

        <!-- Se crea una fila por un estudiante con sus respectivas casillas de calificaciones -->
        <tbody>
        <template style="font-size: 11px" v-for="(enrollment, index) in collectionNotes">
            <row-evaluation :ref="index" :objectInput="{index:index, enrollment:enrollment}"></row-evaluation>
        </template>
        </tbody>
    </table>
</template>

<script>
    import {mapState} from 'vuex'
    import RowEvaluation from './RowEvaluation'
    import RelationPerformances from './RelationPerformances'

    export default {
        name: "table-evaluation",
        data() {
            return {

            }
        },
        components: {
            RowEvaluation, RelationPerformances
        },
        created() {

            this.parameters.forEach(parameter => {
                let refsEvent = parameter.id
                this.$bus.$off("" + refsEvent)
                this.$bus.$on("" + refsEvent, performance => {

                    let elements = this.$refs["parameter" + refsEvent];
                    let s = elements.filter(element => {
                        if (!element.meObject.state) {
                            return element
                        }
                    })

                    if (s.length != 0) {
                        this.$bus.$emit("" + parameter.id + s[0].objectToParameter.id, performance);
                    }

                })

            })

            console.log(this.$store.state.scaleEvaluation)

        },
        mounted() {
            this.$store.state.counterParameter = 0
            this.$store.state.totalInput = 0
            this.$store.state.counterInput = 1

            this.parameters.forEach(parameter => {
                this.$store.state.counterParameter = this.$store.state.counterParameter + parameter.notes_parameter.length
            })

            this.$store.state.totalInput = this.$store.state.counterParameter * this.$store.state.collectionNotes.length + this.$store.state.collectionNotes.length

        },
        computed: {
            ...mapState([
                'parameters',
                'asignature',
                'periodSelected',
                'collectionNotes',
                'counterParameter',
                'totalInput',
                'scaleEvaluation'
            ]),
        },
        methods:{

        }
    }
</script>

<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 3px !important;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }


</style>