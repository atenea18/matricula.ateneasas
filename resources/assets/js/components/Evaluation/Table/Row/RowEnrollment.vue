<template>
    <tr>
        <td style="width:30px"> {{index+1}}</td>
        <td> {{getName}}</td>
        <td style="width:44px">
            <input-no-attendance/>
        </td>
        <template v-for="parameter in stateEvaluation.parameters_selected">
            <td v-for="note_parameter in parameter.notes_parameter">
                <input-note
                        :props-data="{
                        enrollment:enrollment,
                        note_parameter:note_parameter,
                        parameter:parameter}"
                        :ref="getNameLabelAverage(parameter)"/>
            </td>
            <td style="width:41px !important;">
                <label-average-note
                        :props-data="{
                        enrollment:enrollment,
                        parameter:parameter}"
                        :ref="getNameLabelFinal()"/>
            </td>
        </template>
        <td style="width:15px !important;">
            <label-final-note :props-data="{enrollment:enrollment}"/>
        </td>
        <!--
        <td style="padding-top:9px;width:15px" :class="isFinal?'good':'bad'">
            <label v-show="meObject.value">{{meObject.value.toFixed(1)}}</label>
        </td>
        -->
    </tr>
</template>

<script>
    import {mapState} from 'vuex'
    import InputNote from "./Inputs/InputNote";
    import InputNoAttendance from "./Inputs/InputNoAttendance";
    import LabelAverageNote from "./Labels/LabelAverageNote";
    import LabelFinalNote from "./Labels/LabelFinalNote";

    export default {
        name: "row-enrollment",
        props: ['props-data'],
        components: {
            LabelFinalNote,
            LabelAverageNote,
            InputNoAttendance,
            InputNote
        },
        data() {
            return {
                index: this.propsData.index,
                enrollment: this.propsData.enrollment,
                parameters: this.$store.state.stateEvaluation.parameters_selected,
                current_name_note_parameter: ''
            }
        },
        created() {

        },
        mounted() {
            this.flowEventsByTypingInput()
            this.emitEventListInputsNotes()
        },
        computed: {
            ...mapState([
                'configInstitution',
                'stateEvaluation'
            ]),
            getName() {
                return `${this.enrollment.student_last_name} ${this.enrollment.student_name}`
            },

        },
        methods: {
            // Flujo de eventos al escribir en un input
            // En esta función se describe todas las acciones que conlleva el evento de tipeo
            // generado por un inputNote o un inputNoAttendance
            flowEventsByTypingInput() {
                if (!this.$store.state.stateEvaluation.disabled) {
                    this.parameters.forEach(parameter => {
                        parameter.notes_parameter.forEach(note_parameter => {
                            let name_input_note = this.getNameInputNote(parameter, note_parameter)
                            this.$bus.$off(`EventTyped:${name_input_note}@InputNote`)
                            this.$bus.$on(`EventTyped:${name_input_note}@InputNote`, name_label_average => {
                                this.current_name_note_parameter = name_input_note
                                this.emitNotes(name_label_average)
                                // ---------
                                this.emitNotesAverage()
                            });
                        })
                    })
                }
            },
            emitEventListInputsNotes() {
                // Se subscribe a cada evento generado por cada InputNote,
                // donde primero se recorre parameters, y para cada parameteer se recorre
                // note_parameter con el cual se crea el nombre del evento para subscribirse a él
                this.parameters.forEach(parameter => {
                    let name_label_average = this.getNameLabelAverage(parameter)
                    this.emitNotes(name_label_average)
                })
                // ---------
                this.emitNotesAverage()
            },
            emitNotes(name_label_average) {
                let inputs_notes_by_label_average = this.$refs[name_label_average];
                this.$bus.$emit(`EventListInputsNotes:${name_label_average}@RowEnrollment`, inputs_notes_by_label_average);
                
            },

            // Busca todos los LabelAverageNotes, aquel que calcula el promedio de las notas digitada,
            // para sumar los promedios y da como resultado el valor de LabelFinalNote, valor que es emitido
            // a dicho componente
            emitNotesAverage() {

                let sum = 0
                let label_average_by_label_final = this.$refs[this.getNameLabelFinal()];
                label_average_by_label_final.forEach(label_average => {
                    sum += label_average.label_average.value
                })
                this.$bus.$emit(`EventSumLabelsAverage:${this.getNameLabelFinal()}@RowEnrollment`,
                    {sum: sum, name_input_note: this.current_name_note_parameter});

            },

            // compuesto por:
            // enrollment.id-asignature.id-period.id
            // El string retornado sera asignada como una referencia LabelFinalNote,
            getNameLabelFinal() {
                return `${this.enrollment.id}-${this.$store.state.stateEvaluation.asignature_selected.id}-${this.$store.state.stateEvaluation.period_selected.id}`
            },

            // compuesto por:
            // enrollment.id-asignature.id-period.id-parameter.id
            // El string retornado sera asignada como una referencia al InputNote,
            // de acuerdo al parametro que pertenece
            getNameLabelAverage(parameter) {
                return `${this.enrollment.id}-${this.$store.state.stateEvaluation.asignature_selected.id}-${this.$store.state.stateEvaluation.period_selected.id}-${parameter.id}`
            },

            // compuesto por:
            // enrollment.id-asignature.id-period.id-parameter.id-note_parameter.id
            getNameInputNote(parameter, note_parameter) {
                return `${this.enrollment.id}-${this.$store.state.stateEvaluation.asignature_selected.id}-${this.$store.state.stateEvaluation.period_selected.id}-${parameter.id}-${note_parameter.id}`
            },


        }
    }
</script>

<style>

    .error-conexion {
        background-color: #e0e2e5;
    }

    .good {
        color: black;
    }

    .bad {
        color: red;
    }

    .form-controll {
        border-radius: 0px;
        display: block;
        width: 100%;
        height: 28px !important;
        padding: 6px 10px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }
</style>