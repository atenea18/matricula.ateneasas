<template>
    <div style="padding-top:6px !important;padding-left: 3px;padding-right: 2px;">
        <label>{{label_average.value.toFixed(1)}} </label>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "label-average-note",
        props: ['props-data'],
        data() {
            return {
                enrollment: this.propsData.enrollment,
                parameter: this.propsData.parameter,
                basic_note: {
                    percent: 0,
                    sum_notes: 0,
                    counter: 0,
                    average: 0,
                },
                special_note: {
                    average: 0,
                    sum_percent: 0
                },
                label_average:{
                    value:0
                }
            }
        },
        created() {

        },
        mounted(){
            this.subscribeEventByRowEnrollment()
        },
        computed: {
            ...mapState([
                'configInstitution',
                'stateEvaluation',
                'stateScale',
                'stateInformation',
            ]),

            // compuesto por:
            // enrollment.id-asignature.id-period.id-parameter.id
            name_label_average() {
                return `${this.enrollment.id}-${this.$store.state.stateEvaluation.asignature_selected.id}-${this.$store.state.stateEvaluation.period_selected.id}-${this.parameter.id}`
            }
        },
        methods: {
            subscribeEventByRowEnrollment() {
                this.$bus.$on(`EventListInputsNotes:${this.name_label_average}@RowEnrollment`, inputs_notes_by_parameter => {
                    this.initial()
                    inputs_notes_by_parameter.forEach(input_note => {
                        let input_note_selected = {
                            value: parseFloat(input_note.note.value) || 0,
                            percent: (parseFloat(input_note.note.percent)) / 100
                        }
                        //console.log(input_note_selected)
                        this.calculateAverages(input_note_selected)
                    })
                    this.renderTotalAverage()
                })
            },
            calculateAverages(input_note_selected) {
                //Si input_note_selected no tiene porcentaje
                if (input_note_selected.percent == 0) {
                    if(input_note_selected.value > 0){
                        this.basic_note.sum_notes += input_note_selected.value;
                        this.basic_note.counter++;
                    }
                }
                //Si tiene porcentaje
                if (input_note_selected.percent > 0) {
                    this.special_note.average += input_note_selected.value * input_note_selected.percent;
                    this.special_note.sum_percent += input_note_selected.percent;
                }
            },
            assignPercentageToBasicNote(){
                //Se le asigna el porcentaje restante a las notas sin porcentajes
                this.basic_note.percent = (this.parameter.percent / 100) - this.special_note.sum_percent;
            },
            generateAverageBasicNote(){
                if (this.basic_note.counter > 0) {
                    this.basic_note.average = (this.basic_note.sum_notes / this.basic_note.counter) * this.basic_note.percent;
                }
            },
            renderTotalAverage(){
                this.assignPercentageToBasicNote()
                this.generateAverageBasicNote()

                this.label_average.value = (this.basic_note.average + this.special_note.average);
            },

            initial() {
                this.basic_note.average = 0
                this.basic_note.percent = 0
                this.basic_note.counter = 0
                this.basic_note.sum_notes = 0

                this.special_note.average = 0
                this.special_note.sum_percent = 0
            }
        }
    }
</script>

<style scoped>

</style>