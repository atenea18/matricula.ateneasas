<template>
    <div>
        <input v-debounce="debounceObject.delay" :class="attendance.is_send?'sendAttendance':'not-send'"
               @keydown="eventDisplacement" :id="'input'+displacement.counter"
               class="form-controll"
               style="padding:2px 2px" type="text" v-model.lazy="attendance.value">
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import debounce from '../../../../../v-debounce/index'

    export default {
        name: "input-no-attendance",
        props: ['props-data'],
        directives: {debounce},
        data() {
            return {
                debounceObject: {
                    delay: 2000
                },
                attendance: {
                    value: '',
                    before_value: '',
                    is_send: true
                },
                displacement: {
                    counter: 0,
                    total_notes_parameters: 0,
                    total_inputs: 0,
                },
                enrollment: this.propsData.enrollment,
                is_first: true
            }
        },
        created() {
            this.attendance.value = this.enrollment.no_attendance
            this.attendance.before_value = this.enrollment.no_attendance
        },
        updated() {
            this.is_first = false
        },
        mounted() {
            this.displacement.counter = this.$store.state.stateEvaluation.displacement.counter_input
            this.$store.state.stateEvaluation.displacement.counter_input++

            this.$bus.$on(`EventSaveNoAttendance:${this.name_input_attendance}@LabelFinalNote`, () => {
                this.attendance.before_value = this.attendance.value
                this.attendance.is_send = true
            });
        },
        watch: {
            'attendance.value': function () {
                this.style()
                this.eventWrite()
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
            name_input_attendance() {
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

                let val = parseFloat(this.attendance.value)

                if (!isNaN(val) || this.attendance.value == '') {
                    this.emitEventNoteTyped()
                } else {
                    if (this.attendance.value == '') {
                        this.emitEventNoteTyped()
                    } else {
                        this.attendance.value = this.attendance.before_value
                        this.attendance.is_send = true
                    }
                }
            },

            emitEventNoteTyped() {
                let is_conexion = this.$store.state.stateInformation.is_conexion

                if (!this.$store.state.stateEvaluation.disabled) {
                    if (this.attendance.value == '' || this.attendance.value > 0) {
                        if (this.attendance.before_value != this.attendance.value && is_conexion) {
                            // Se emite evento para dar a conocer, que el valor digitado es correcto,
                            // y puede ser tomado para realizar los cálculos, para el promedio de nota correpondiente
                            // al parametro
                            this.$bus.$emit(`EventTyped:${this.name_input_attendance}@InputNoAttendance`, this.attendance)
                        }
                    } else {
                        this.attendance.value = this.attendance.before_value
                        this.attendance.is_send = true
                    }
                } else {
                    if (!this.is_first) {
                        toastr.error('Periodo deshabilitado')
                        //this.note.value = this.note.before_value
                        //this.note.is_send = true
                    }
                }
            },

            eventDisplacement(e) {
                this.displacement.total_notes_parameters = this.$store.state.stateEvaluation.displacement.total_notes_parameters

                //rigth
                if (e.keyCode == 39) {
                    let nextInput = this.displacement.counter + 1
                    this.setFocusElement(nextInput)
                }

                //left
                if (e.keyCode == 37) {
                    let nextInput = this.displacement.counter - 1
                    this.setFocusElement(nextInput)
                }
                //down
                if (e.keyCode == 40) {
                    let nextInput = this.displacement.counter + this.displacement.total_notes_parameters
                    this.setFocusElement(nextInput)
                }
                //up
                if (e.keyCode == 38) {
                    let nextInput = this.displacement.counter - this.displacement.total_notes_parameters
                    this.setFocusElement(nextInput)
                }
            },
            setFocusElement(nextInput) {
                this.displacement.total_inputs = this.$store.state.stateEvaluation.displacement.total_inputs

                if (nextInput > 0 && nextInput <= this.displacement.total_inputs) {
                    let element = document.getElementById('input' + nextInput)
                    element.focus()
                }
            },
            stateGetConexion() {
                this.$store.dispatch('verifyConexionX')
            },
            style() {
                if (!this.is_first) {
                    if (this.attendance.before_value != this.attendance.value) {
                        this.attendance.is_send = false
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