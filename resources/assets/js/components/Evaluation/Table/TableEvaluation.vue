<template>
    <table class="table table-bordered">

        <!-- Fila de titulos -->
        <thead>
        <tr style="font-size: 11px">
            <th rowspan="2"> No.</th>
            <th rowspan="2"> NOMBRES Y APELLIDOS</th>
            <th rowspan="2"> FAA</th>
            <template v-for="parameter in stateEvaluation.parameters_selected">
                <th :colspan="parameter.notes_parameter.length+1" style="text-align: center">
                    {{parameter.parameter}}
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
            <template v-for="parameter in stateEvaluation.parameters_selected">
                <template v-for="note_parameter in parameter.notes_parameter">
                    <relation-performances v-if="note_parameter.notes_type_id == 1" :ref="'parameter'+parameter.id"
                                           :props-data="{note_parameter:note_parameter}">
                    </relation-performances>
                    <relation-criterias v-if="note_parameter.notes_type_id != 1"
                                        :props-data="{note_parameter:note_parameter}">
                    </relation-criterias>
                    <!-- style="width: 44px !important; font-size: 10px !important;"-->
                </template>
                <th></th>
            </template>
            <!--
            <th> VAL </th>
            -->
        </tr>
        </thead>
        <!-- Se crea una fila por un estudiante con sus respectivas casillas de calificaciones -->
        <tbody>
        <template style="font-size: 11px" v-for="(enrollment, index) in stateEvaluation.collectionNotes">
            <row-enrollment :ref="index" :propsData="{index:index, enrollment:enrollment}"/>
        </template>
        </tbody>

    </table>
</template>

<script>
    import {mapState} from 'vuex'
    import RelationPerformances from "./Relation/RelationPerformances";
    import RelationCriterias from "./Relation/RelationCriterias";
    import RowEnrollment from "./Row/RowEnrollment";

    export default {
        components: {
            RowEnrollment,
            RelationCriterias,
            RelationPerformances
        },
        name: "table-evaluation",
        computed: {
            ...mapState([
                'configInstitution',
                'stateEvaluation',
                'stateInformation'
            ]),
        },
        data() {
            return {
                parameters_selected: this.$store.state.stateEvaluation.parameters_selected,
                num_rows: this.$store.state.stateEvaluation.collectionNotes.length,
            }
        },
        created() {
            this.clearStateDataDisplacement()
        },
        mounted() {
            this.initStateDataDisplacement()
        },
        methods: {
            // Se define los valores acerca de las cantidades de input renderizados,
            // cantidad de filas, cantidad de input por cada fila
            // estos datos es utilizado por el componente InputNote para generar el deplazamiento
            initStateDataDisplacement() {
                let total_inputs = 0
                let total_notes_parameters = 0
                //Un input más por cada enrollment
                let one_input_of_no_attendance = 1

                this.parameters_selected.forEach(parameter => {
                    total_notes_parameters = total_notes_parameters + parameter.notes_parameter.length
                })

                total_inputs = (total_notes_parameters + one_input_of_no_attendance) * this.num_rows

                this.$store.state.stateEvaluation.displacement.total_notes_parameters = (total_notes_parameters + one_input_of_no_attendance)
                this.$store.state.stateEvaluation.displacement.total_inputs = total_inputs
                this.$store.state.stateEvaluation.displacement.num_rows_enrollment = this.num_rows
            },
            // Se limpia los datos anteriormente asignado por la función initStateDataDisplacement
            // a estas variables globales
            clearStateDataDisplacement(){
                this.$store.state.stateEvaluation.displacement.total_inputs = 0
                this.$store.state.stateEvaluation.displacement.counter_input = 1
                this.$store.state.stateEvaluation.displacement.total_notes_parameters = 0
                this.$store.state.stateEvaluation.displacement.num_rows_enrollment = 0
            },
        }

    }
</script>

<style>
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
        padding: 3px !important;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

</style>