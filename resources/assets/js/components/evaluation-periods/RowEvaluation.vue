<template>
    <tr>
        <td style="width:30px">{{objectToEvaluation.index+1}}</td>
        <td> {{fullName}}</td>
        <input-no-attendance :objectInput="objectToEvaluation"></input-no-attendance>
        <template v-for="parameter in parameters">
            <td v-for="noteParameter in parameter.notes_parameter">
                <input-evaluation
                        :ref="referenceEvaluationPeriodsId+parameter.id"
                        :objectInput="objectToEvaluation" :noteparameter="noteParameter"
                        :parameter="parameter"></input-evaluation>
            </td>
            <input-parameter :ref="refsInputParameter" :objectInput="objectToEvaluation" :parameter="parameter"/>
        </template>
        <td style="padding-top:9px;width:15px" :class="isFinal?'good':'bad'">
            <label v-show="meObject.value">{{meObject.value.toFixed(2)}}</label>
        </td>
    </tr>
</template>

<script>
    import {mapState, mapMutations, mapGetters} from "vuex";
    import InputEvaluation from "./InputEvaluation";
    import InputParameter from "./InputParameter";
    import InputNoAttendance from "./InputNoAttendance";

    export default {
        name: "row-evaluation",
        components: {
            InputNoAttendance,
            InputEvaluation, InputParameter
        },
        props: {
            objectInput: {type: Object}
        },

        data() {
            return {

                objectToEvaluation: {
                    index: 0,
                    enrollment: {},
                },
                meObject: {
                    value: 0,
                    isInit: false,
                    evaluationPeriodId: 0,
                    isloaded: false
                },
                isFinal: true

            }
        },

        created() {
            this.objectToEvaluation = this.objectInput;

        },
        mounted() {
            this.onEventDirtyInputEvaluation()
            this.emitNotesBelongToParameters()
            this.calculateSumForNoteFinal()

        },

        updated() {
            this.getConexion()
        },

        computed: {
            ...mapState([
                "parameters",
                "asignature",
                "periodSelected,",
                "isConexion",
                "periodObjectSelected",
                'dateNow'
            ]),

            fullName() {
                return this.objectToEvaluation.enrollment.student_last_name + " " + this.objectToEvaluation.enrollment.student_name
            },
            refsInputParameter() {
                return "" + this.objectToEvaluation.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected
            },
            referenceEvaluationPeriodsId() {
                return "" + this.objectToEvaluation.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected
            },
            parametersAll() {
                return this.$store.state.parameters;
            }
        },

        methods: {

            emitNotesBelongToParameters() {
                // Emite un evento para que cada componente input-parameter pueda conocer
                // los componentes input-evaluation que le corresponde
                this.parameters.forEach(parameter => {
                    let refEvaluationPeriodsMoreParameter = this.referenceEvaluationPeriodsId + parameter.id
                    this.emitNotes(refEvaluationPeriodsMoreParameter)
                })
            },
            /*
              onEventDirtyInputEvaluation
             */
            onEventDirtyInputEvaluation() {
                let date = this.$store.state.dateNow.getTime()
                let start_date = new Date(this.$store.state.periodObjectSelected.start_date).getTime()
                let end_date = new Date(this.$store.state.periodObjectSelected.end_date).getTime()

                if(start_date < date && date < end_date){
                    this.onEventOfKeyUpInputNoAttendance()
                    this.assignmentEventToInputEachNotesParameter()
                    this.meObject.isInit = true;
                    console.log("Si se cumple")
                }
            },

            onEventOfKeyUpInputNoAttendance() {

                let refEvaluationPeriodsId = this.referenceEvaluationPeriodsId

                this.$bus.$off("dirty-input-no-attendance-" + refEvaluationPeriodsId)
                this.$bus.$on("dirty-input-no-attendance-" + refEvaluationPeriodsId, object => {

                    this.saveNoAttendance(object)
                });

            },

            saveNoAttendance(object) {
                let _this = this
                let evaluation_periods_id = object._props.objectInput.enrollment.evaluation_periods_id


                if (evaluation_periods_id == 0) {
                    this.saveEvaluationPeriods(null, null, this.callbackSaveNoAttendance, object)
                } else {
                    this.callbackSaveNoAttendance(object)
                }


                /*

                    */
            },

            callbackSaveNoAttendance(object) {
                let data = {
                    quantity: object.quantity,
                    evaluation_periods_id: this.objectToEvaluation.enrollment.evaluation_periods_id,
                }
                let _this = this
                let refEvaluationPeriodsId = this.referenceEvaluationPeriodsId


                axios.post('/teacher/evaluation/storeNoAttendance', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            _this.$bus.$emit("no-attendance-saved" + refEvaluationPeriodsId);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                //console.log(data)

            },

            assignmentEventToInputEachNotesParameter() {
                this.parameters.forEach(parameter => {
                    parameter.notes_parameter.forEach(noteParameter => {
                        let refEnrollmentMoreNotesParameter = this.referenceEvaluationPeriodsId + parameter.id + noteParameter.id
                        this.onEventOfKeyUpInputEvaluation(refEnrollmentMoreNotesParameter)
                    })
                });
            },

            onEventOfKeyUpInputEvaluation(refEnrollmentMoreNotesParameter) {
                this.$bus.$off("set-dirty-" + refEnrollmentMoreNotesParameter)
                this.$bus.$on("set-dirty-" + refEnrollmentMoreNotesParameter, (refEvaluationPeriodsMoreParameter) => {

                    this.emitNotes(refEvaluationPeriodsMoreParameter)
                    this.calculateSumForNoteFinal()
                    this.sendDataEvaluationPeriods(refEnrollmentMoreNotesParameter)
                });
            },

            emitNotes(refEvaluationPeriodsMoreParameter) {
                // Recibe un estring que identifica el conjunto de input-evaluation
                // Y emite el evento para que cada input-parameter pueda conocer a sus correspondientes
                // input-evaluation
                let notesBelongToParameter = this.$refs[refEvaluationPeriodsMoreParameter];
                //console.log(notesBelongToParameter[0].valuenote)
                this.$bus.$emit("set-notes-to-parameter-" + refEvaluationPeriodsMoreParameter, notesBelongToParameter);
            },

            calculateSumForNoteFinal() {
                // Calcula la suma de todos los input-parameter
                this.meObject.value = 0;
                let notesParameters = this.$refs[this.refsInputParameter];
                notesParameters.forEach(e => {
                    this.meObject.value += e.value
                })

                if (typeof this.objectToEvaluation.enrollment.notes_final != 'undefined') {

                    if (this.meObject.value.toFixed(2) != this.objectToEvaluation.enrollment.notes_final.value.toFixed(2)) {
                        this.meObject.evaluationPeriodId = this.objectToEvaluation.enrollment.notes_final.evaluation_periods_id
                        this.sendDataFinalNotes()
                    }
                }
            },

            sendDataEvaluationPeriods(refEnrollmentMoreNotesParameter) {
                // Evita que se ejecute la petición al crearse el componente
                if (this.meObject.isInit) {

                    if (this.objectToEvaluation.enrollment.evaluation_periods_id != 0 && typeof this.objectToEvaluation.enrollment.evaluation_periods_id != 'undefined') {
                        //console.log(this.objectToEvaluation.enrollment.evaluation_periods_id)
                        this.saveNotes(refEnrollmentMoreNotesParameter, this.objectToEvaluation.enrollment.evaluation_periods_id)
                    } else {

                        this.saveEvaluationPeriods(refEnrollmentMoreNotesParameter, this.saveNotes)
                    }
                }
            },

            saveEvaluationPeriods(refEnrollmentMoreNotesParameter, callbackSaveNotes, callbackSaveNoAttendance = null, object = null) {
                let _this = this

                let data = {
                    enrollment_id: this.objectToEvaluation.enrollment.id,
                    periods_id: this.$store.state.periodSelected,
                    asignatures_id: this.$store.state.asignature.id
                }
                axios.post('/teacher/evaluation/storeEvaluationPeriods', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            _this.objectToEvaluation.enrollment.evaluation_periods_id = response.data.id
                            if (callbackSaveNotes != null) {
                                callbackSaveNotes(refEnrollmentMoreNotesParameter, _this.objectToEvaluation.enrollment.evaluation_periods_id)
                            } else {
                                callbackSaveNoAttendance(object)
                            }


                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            saveNotes(refEnrollmentMoreNotesParameter, evaluationPeriodsId) {
                this.meObject.evaluationPeriodId = evaluationPeriodsId
                this.sendDataFinalNotes()
                this.$bus.$emit('i-can-save-note-' + refEnrollmentMoreNotesParameter, evaluationPeriodsId)
            },

            sendDataFinalNotes() {
                this.isFinal = false
                let _this = this
                let data = {
                    value: this.meObject.value.toFixed(2),
                    overcoming: null,
                    evaluation_periods_id: this.meObject.evaluationPeriodId,
                }
                axios.post('/teacher/evaluation/storeFinalNotes', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            _this.isFinal = true
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            getConexion() {
                this.$store.dispatch('verifyConexion')
                if (!this.$store.state.isConexion) {
                    this.$swal({
                        position: 'top-end',
                        type: 'error',
                        title: 'No hay conexión a internet',
                        showConfirmButton: true,
                        timer: 4000
                    })
                }
            }


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