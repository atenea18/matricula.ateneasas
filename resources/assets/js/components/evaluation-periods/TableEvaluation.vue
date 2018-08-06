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
            <th rowspan="2"> VAL</th>
        </tr>
        <tr>
            <!--
            <th></th>
            <th></th>
            <th></th>
            -->
            <template v-for="para in parameters">
                <template v-for="note in para.notes_parameter">
                    <relation-performances v-if="note.notes_type_id == 1" :objectInput="note"
                                           :ref="'parameter'+para.id">
                    </relation-performances>

                    <th v-else="note.notes_type_id == 1" style="width: 44px !important; font-size: 10px !important;">
                       <span v-if="note.criterias" data-toggle="tooltip" data-placement="bottom"
                             :title="note.criterias.criterias_name">
                           {{note.criterias.criterias_abbreviation || ""}}
                       </span>
                    </th>

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
            return {}
        },
        components: {
            RowEvaluation, RelationPerformances
        },
        computed: {
            ...mapState([
                'parameters',
                'asignature',
                'periodSelected',
                'collectionNotes',
                'counterParameter',
                'totalInput',
                'scaleEvaluation',
                'periodObjectSelected',
                'dateNow',
                'maxScale',
                'minScale',
                'configInstitution'
            ]),
        },
        created() {

            this.parameters.forEach(parameter => {
                let refsEvent = parameter.id
                this.$bus.$on("" + refsEvent, performance => {

                    let elements = this.$refs["parameter" + refsEvent];
                    if (elements) {
                        //console.log(elements)
                        let s = elements.filter(element => {
                            if (!element.mainComponentObject.state) {
                                return element
                            }
                        })

                        if (s.length != 0) {
                            this.$bus.$emit("" + parameter.id + s[0].objectToParameter.id, performance);
                        }

                    }


                })
            })

            this.generateLimitNotes()


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
        methods: {
            generateLimitNotes() {

                this.$store.state.scaleEvaluation.forEach(element => {
                    if (this.$store.state.maxScale < element.rank_end) {
                        this.$store.state.maxScale = element.rank_end
                    }
                    if (this.$store.state.minScale > element.rank_start) {
                        this.$store.state.minScale = element.rank_start
                    }
                })

                //console.log("max: " + this.$store.state.maxScale)
                //console.log("min: " + this.$store.state.minScale)
            }
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