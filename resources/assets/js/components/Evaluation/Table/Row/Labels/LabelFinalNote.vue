<template>
    <div style="padding-top:6px;">
        <label v-show="true" :class="label_final.is_send?'send_black':'not_danger'">
            {{label_final.value.toFixed(1)}}</label>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "label-final-note",
        props: ['props-data'],
        data() {
            return {
                enrollment: {
                    id:0,
                    evaluation_periods_id:0,
                    notes_final:{
                        value:0
                    }
                },
                label_final: {
                    value: 0,
                    is_send: true,
                    name_input_note_action: ''
                },
                is_first: false
            }
        },
        created() {

        },
        mounted(){
            this.enrollment.id = this.propsData.enrollment.id
            this.enrollment.evaluation_periods_id = this.propsData.enrollment.evaluation_periods_id
            if(this.propsData.enrollment.hasOwnProperty('notes_final')){
                this.enrollment.notes_final.value = this.propsData.enrollment.notes_final.value
            }
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
            // enrollment.id-asignature.id-period.id
            name_label_final() {
                return `${this.enrollment.id}-${this.$store.state.stateEvaluation.asignature_selected.id}-${this.$store.state.stateEvaluation.period_selected.id}`
            }
        },
        methods: {
            subscribeEventByRowEnrollment() {
                this.$bus.$off(`EventSumLabelsAverage:${this.name_label_final}@RowEnrollment`)
                this.$bus.$on(`EventSumLabelsAverage:${this.name_label_final}@RowEnrollment`, info => {

                    this.label_final.value = info.sum
                    this.label_final.name_input_note_action = info.name_input_note
                    this.verifyPropertyEnrollmentNoteFinal()
                })
            },

            verifyPropertyEnrollmentNoteFinal() {
                // Si ya esta guardado evaluation period
                if (this.enrollment.evaluation_periods_id !== undefined) {

                    // Si ya tiene una nota final
                    if (this.enrollment.hasOwnProperty('notes_final')) {
                        //comparo nota final calculada con la que ya se tiene

                        this.compareToValues()
                    }
                    //Guardar nota final
                    else {
                        if (this.label_final.value > 0) {
                            this.emitToNoteInput()
                            this.saveNoteFinal(this.enrollment.evaluation_periods_id)
                            //console.log('Sin Nota final: '+this.label_final.value)
                        }
                    }
                } else {
                    if (this.is_first) {
                        this.saveEvaluationPeriod()
                    }
                }
                this.is_first = true
            },

            compareToValues() {
                this.emitToNoteInput()
                if (parseFloat(this.enrollment.notes_final.value).toFixed(1) != parseFloat(this.label_final.value).toFixed(1)) {
                    this.enrollment.notes_final.value = parseFloat(this.label_final.value).toFixed(1)
                    this.saveNoteFinal(this.enrollment.evaluation_periods_id)
                    //console.log('Nota calculada diferente, procede hacer petición para guardar')
                }
            },
            saveEvaluationPeriod() {
                let _this = this

                let data = {
                    enrollment_id: this.enrollment.id,
                    periods_id: this.$store.state.stateEvaluation.period_selected.id,
                    asignatures_id: this.$store.state.stateEvaluation.asignature_selected.id
                }
                axios.post('/ajax/evaluation-period/store', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            _this.enrollment.evaluation_periods_id = response.data.id
                            _this.emitToNoteInput()
                            _this.saveNoteFinal(response.data.id)
                        }
                    })
                    .catch(function (error) {
                        console.log('period'+error);
                    });

                console.log('Guarda Evaluatioon Period')
            },

            saveNoteFinal(evaluation_periods_id) {
                this.label_final.is_send = false
                let _this = this

                let data = {
                    value: parseFloat(this.label_final.value).toFixed(1),
                    evaluation_periods_id: evaluation_periods_id
                }
                axios.post('/ajax/evaluation-store-note-final', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            _this.enrollment.notes_final.value = parseFloat(_this.label_final.value).toFixed(1)
                            _this.label_final.is_send = true
                        }
                    })
                    .catch(function (error) {
                        console.log('final'+error);
                    });
            },
            emitToNoteInput() {
                if (this.label_final.name_input_note_action != '') {
                    this.$bus.$emit(`EventNoteCanSave:${this.label_final.name_input_note_action}@LabelFinalNote`,
                        this.enrollment.evaluation_periods_id)
                }
            }
        },
    }
</script>

<style>
    .send_black {
        color: #000000;
    }

    .not_danger {
        color: red;
    }
</style>