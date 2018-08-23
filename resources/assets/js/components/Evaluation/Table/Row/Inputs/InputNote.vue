<template>
    <div>
        <input v-debounce="debounceObject.delay" :class="note.is_send?'send':'not-send'"
               @keydown="eventDisplacement" :id="'input'+displacement.counter"
               class="form-controll"
               style="padding:2px 2px" type="text" v-model.lazy="note.value">
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import debounce from '../../../../../v-debounce/index'

    export default {
        name: "input-note",
        props: ['props-data'],
        directives: {debounce},
        data() {
            return {
                debounceObject: {
                    delay: 2000
                },
                note: {
                    value: '',
                    before_value: '',
                    percent: this.propsData.note_parameter.percent,
                    is_send: true,

                },
                displacement: {
                    counter: 0,
                    total_notes_parameters: 0,
                    total_inputs: 0,
                },
                note_parameter: this.propsData.note_parameter,
                enrollment: this.propsData.enrollment,
                parameter: this.propsData.parameter,
                is_first: true

            }
        },
        created() {

            this.findNote(this.propsData.note_parameter.id)

        },
        updated() {
            this.is_first = false
        },
        mounted() {

            this.$bus.$off(`EventNoteCanSave:${this.name_input_note}@LabelFinalNote`)
            this.$bus.$on(`EventNoteCanSave:${this.name_input_note}@LabelFinalNote`, evaluation_periods_id => {
                this.saveNote(evaluation_periods_id)
                //console.log('soy nota: ' + this.name_input_note + " Evaluation_id=" + evaluation_periods_id)
            })

            this.displacement.counter = this.$store.state.stateEvaluation.displacement.counter_input
            this.$store.state.stateEvaluation.displacement.counter_input++
        },
        watch: {
            'note.value': function () {
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
            // enrollment.id-asignature.id-period.id-parameter.id-note_parameter.id
            name_input_note() {
                return `${this.enrollment.id}-${this.$store.state.stateEvaluation.asignature_selected.id}-${this.$store.state.stateEvaluation.period_selected.id}-${this.parameter.id}-${this.note_parameter.id}`
            },

            // compuesto por:
            // enrollment.id-asignature.id-period.id-parameter.id
            name_label_average() {
                return `${this.enrollment.id}-${this.$store.state.stateEvaluation.asignature_selected.id}-${this.$store.state.stateEvaluation.period_selected.id}-${this.parameter.id}`
            }
        },
        methods: {
            saveNote(key) {

                let data = {
                    value: this.note.value,
                    evaluation_periods_id: key,
                    notes_parameters_id: this.note_parameter.id
                }
                let _this = this
                axios.post('/ajax/evaluation-store-note', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            //console.log("guardo notas")
                            _this.note.is_send = true
                            _this.note.before_value= _this.note.value
                        }
                    })
                    .catch(function (error) {
                        console.log('note'+error);
                    });
            },
            // Se ejecuta cuando "note.value" cambia su valor, y se encarga de filtrar el valor introducido
            // me diantes distintas restricciones
            eventWrite() {
                this.stateGetConexion()
                if (!this.is_first) {
                    if (!this.$store.state.stateInformation.is_conexion) {
                        toastr.error('Sin internet')
                    }
                }

                let val = parseFloat(this.note.value)

                if (!isNaN(val) || this.note.value == '') {
                    this.emitEventNoteTyped()
                } else {
                    if (this.note.value == '') {
                        this.emitEventNoteTyped()
                    } else {
                        this.note.value = this.note.before_value
                        this.note.is_send = true
                    }
                }
            },

            // Se encarga de emitir un evento cuando verifica que el valor introducido
            // no se exceda de lo permitido por la escala de evaluación
            emitEventNoteTyped() {
                let min_scale = this.$store.state.stateScale.min_scale
                let max_scale = this.$store.state.stateScale.max_scale
                let is_conexion = this.$store.state.stateInformation.is_conexion
                let filter_scale = (this.note.value >= min_scale && max_scale >= this.note.value)

                if (!this.$store.state.stateEvaluation.disabled) {
                    if (this.note.value == '' || filter_scale) {
                        if (this.note.before_value != this.note.value && is_conexion) {
                            // Se emite evento para dar a conocer, que el valor digitado es correcto,
                            // y puede ser tomado para realizar los cálculos, para el promedio de nota correpondiente
                            // al parametro
                            this.$bus.$emit(`EventTyped:${this.name_input_note}@InputNote`, this.name_label_average)
                            //Por el momento-- esto se va a quitar luego
                        }
                    } else {
                        this.note.value = this.note.before_value
                        this.note.is_send = true
                    }
                } else {
                    if (!this.is_first) {
                        toastr.error('Periodo deshabilitado')
                        //this.note.value = this.note.before_value
                        //this.note.is_send = true
                    }
                }
            },
            // InputNote representa un note_parameter, esta relacionado directamente con una nota
            // de un estudiante en una asignatura específica, está función se encarga de contrar
            // en el arreglo de notas del estudiante actual, la nota que le pertenece a dicho InputNote
            findNote(note_parameter_id) {
                if (this.enrollment.notes.length != 0) {
                    let note_selected = this.enrollment.notes.find((note) => {
                        if (note.notes_parameters_id == note_parameter_id) {
                            return note
                        }
                    })
                    if (note_selected) {
                        this.note.value = note_selected.value == null ? '' : note_selected.value
                        this.note.before_value = this.note.value
                    }
                }
            },

            // Evento que se encarga en el desplazamiento del cursos por medio de los InputNote
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
                    if (this.note.before_value != this.note.value) {
                        this.note.is_send = false
                    }
                }
            },
        }
    }
</script>

<style>
    .send {
        background-color: #96f590;
    }

    .not-send {
        background-color: #ff9794;
    }
</style>