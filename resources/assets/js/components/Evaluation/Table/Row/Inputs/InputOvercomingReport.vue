<template>
    <div>
        <input v-debounce="debounceObject.delay" :class="over_report.is_send?'sendAttendance':'not-send'"
               @keydown="eventDisplacement" :id="'inputover'+displacement.counter_input_over"
               class="form-controll"
               style="padding:2px 2px" type="text" v-model.lazy="over_report.value">
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import debounce from '../../../../../v-debounce/index'

    export default {
        name: "input-overcoming-report",
        props: ['props-data'],
        directives: {debounce},
        data() {
            return {
                debounceObject: {
                    delay: 2000
                },
                over_report: {
                    value: '',
                    before_value: '',
                    is_send: true
                },
                displacement: {
                    counter: 0,
                    total_notes_parameters: 0,
                    total_inputs: 0,
                    counter_input_over: 0
                },
                enrollment: this.propsData.enrollment,
                is_first: true
            }
        },
        created() {
            if(typeof this.enrollment.report_asignature != "undefined"){
                this.over_report.value = this.enrollment.report_asignature.overcoming
                this.over_report.before_value = this.enrollment.report_asignature.overcoming
            }
        },
        updated() {
            this.is_first = false
        },
        mounted() {
            this.displacement.counter_input_over = this.$store.state.stateEvaluation.displacement.counter_input_over
            this.$store.state.stateEvaluation.displacement.counter_input_over++

            this.$bus.$on(`EventSaveOverReport:${this.name_input}@LabelFinalNote`, () => {
                this.over_report.before_value = this.over_report.value
                this.over_report.is_send = true
            });
        },
        watch: {
            'over_report.value': function () {
                this.style()
                if(typeof this.enrollment.report_asignature != "undefined"){
                    this.eventWrite()
                }

            }
        },
        computed: {
            ...mapState([
                'configInstitution',
                'stateEvaluation',
                'stateScale',
                'stateInformation',
            ]),
            // compuesto por:
            // enrollment.id-asignature.id-period.id-
            name_input() {
                return `${this.enrollment.id}-${this.$store.state.stateEvaluation.asignature_selected.id}-${this.$store.state.stateEvaluation.period_selected.id}`
            }
        },
        methods: {

            eventWrite() {
                this.stateGetConexion()
                if (!this.is_first) {
                    if (!this.$store.state.stateInformation.is_conexion) {
                        toastr.error('Sin internet')
                    }
                }

                let val = parseFloat(this.over_report.value)

                if (!isNaN(val) || this.over_report.value == '') {
                    this.emitEventNoteTyped()
                } else {
                    if (this.over_report.value == '') {
                        this.emitEventNoteTyped()
                    } else {
                        this.over_report.value = this.over_report.before_value
                        this.over_report.is_send = true
                    }
                }
            },

            emitEventNoteTyped() {
                let min_scale = this.$store.state.stateScale.min_scale
                let max_scale = this.$store.state.stateScale.max_scale
                let is_conexion = this.$store.state.stateInformation.is_conexion
                let filter_scale = (this.over_report.value >= min_scale && max_scale >= this.over_report.value)

                if (true) {
                    if (this.over_report.value == '' || filter_scale) {
                        if (this.over_report.before_value != this.over_report.value && is_conexion) {
                            this.$bus.$emit(`EventTyped:${this.name_input}@InputOvercomingReport`, this.over_report)
                        }
                    } else {
                        this.over_report.value = this.over_report.before_value
                        this.over_report.is_send = true
                    }
                } else {
                    if (!this.is_first) {
                        toastr.error('Periodo deshabilitado')
                    }
                }
            },

            eventDisplacement(e) {

                //down
                if (e.keyCode == 40) {
                    let nextInput = this.displacement.counter_input_over + 1
                    this.setFocusElement(nextInput)
                }
                //up
                if (e.keyCode == 38) {
                    let nextInput = this.displacement.counter_input_over - 1
                    if(nextInput > 0)
                        this.setFocusElement(nextInput)

                }
            },
            setFocusElement(nextInput) {
                this.displacement.total_inputs = this.$store.state.stateEvaluation.displacement.total_inputs

                if (nextInput > 0 && nextInput <= this.displacement.total_inputs) {
                    let element = document.getElementById('inputover' + nextInput)
                    element.focus()
                }
            },
            stateGetConexion() {
                this.$store.dispatch('verifyConexionX')
            },
            style() {
                if (!this.is_first) {
                    if (this.over_report.before_value != this.over_report.value) {
                        this.over_report.is_send = false
                    }
                }
            },
        }
    }
</script>

<style>
    .sendAttendance {
        background-color: #09c8f5;
    }

</style>