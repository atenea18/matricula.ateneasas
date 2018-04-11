<template>
    <tr>
        <td style="width:30px">{{objectToEvaluation.index+1}}</td>
        <td > {{fullName}}</td>
        <td style="width:30px">

        </td>
        <template v-for="(parameter,i) in parameters">
            <td v-for="noteParameter in parameter.notes_parameter">
                <input-evaluation
                        :ref="refsInputEvaluation+parameter.id"
                        :objectInput="objectToEvaluation" :noteparameter="noteParameter"
                        :parameter="parameter"></input-evaluation>
            </td>
            <input-parameter :ref="refsInputParameter" :objectInput="objectToEvaluation" :parameter="parameter"/>
        </template>
        <td style="padding-top:9px;width:15px">
            <label v-show="meObject.value">{{meObject.value.toFixed(2)}}</label>
        </td>
    </tr>
</template>

<script>
    import {mapState, mapMutations, mapGetters} from "vuex";
    import InputEvaluation from "./InputEvaluation";
    import InputParameter from "./InputParameter";

    export default {
        name: "row-evaluation",
        components: {InputEvaluation, InputParameter},
        props: {
            objectInput: {type: Object}
        },

        data() {
            return {

                objectToEvaluation: {
                    index: 0,
                    enrollment: {},
                },
                meObject:{
                    value:0,
                    isInit:false,
                    evaluationPeriodId:0,
                }
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
            ...mapState(["parameters", "asignature", "periodSelected"]),

            fullName() {
                return this.objectToEvaluation.enrollment.student_last_name + " " + this.objectToEvaluation.enrollment.student_name
            },
            refsInputParameter() {
                return "" + this.objectToEvaluation.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected
            },
            refsInputEvaluation() {
                return "" + this.objectToEvaluation.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected
            },
            parametersAll() {
                return this.$store.state.parameters;
            }
        },

        methods: {

            calculateSumForNoteFinal() {
                // Calcula la suma de todos los input-parameter
                this.meObject.value = 0;
                let notesParameters = this.$refs[this.refsInputParameter];
                notesParameters.forEach(e => {
                    this.meObject.value += e.value
                })
            },

            emitNotesBelongToParameters() {
                // Emite un evento para que cada componente input-parameter pueda conocer
                // los componentes input-evaluation que le corresponde
                this.parameters.forEach(parameter => {
                    let refsEvent = this.refsInputEvaluation + parameter.id
                    this.emitNotes(refsEvent)
                })
            },

            /*
             *  onEventDirtyInputEvaluation
             */
            onEventDirtyInputEvaluation() {

                this.parameters.forEach(parameter => {

                    parameter.notes_parameter.forEach(noteParameter => {
                        let refsEvent = this.refsInputEvaluation + parameter.id + noteParameter.id


                        this.$bus.$off("set-dirty-" + refsEvent)
                        this.$bus.$on("set-dirty-" + refsEvent, keyToSearch => {
                            //
                            let ref = this.refsInputEvaluation + parameter.id + 1

                            let notesBelongToParameter = this.$refs[ref];
                            //console.log(notesBelongToParameter)
                            //
                            this.emitNotes(keyToSearch)

                            this.calculateSumForNoteFinal()

                            this.sendDataEvaluationPeriods(refsEvent)
                        });
                    })


                });
                this.meObject.isInit = true;
            },

            emitNotes(refsEvent) {
                // Recibe un estring que identifica el conjunto de input-evaluation
                // Y emite el evento para que cada input-parameter pueda conocer a sus correspondientes
                // input-evaluation
                let notesBelongToParameter = this.$refs[refsEvent];
                //console.log(notesBelongToParameter[0].valuenote)
                this.$bus.$emit("set-notes-to-parameter-" + refsEvent, notesBelongToParameter);
            },

            sendDataEvaluationPeriods(keyRef){

                    let data = {
                        enrollment_id: this.objectToEvaluation.enrollment.id,
                        periods_id: this.$store.state.periodSelected,
                        asignatures_id: this.$store.state.asignature.id
                    }
                    let _this = this

                    // Evita que se ejecute la petición al crearse el componente
                    if (this.meObject.isInit) {

                        axios.post('/teacher/evaluation/storeEvaluationPeriods', {data})
                            .then(function (response) {

                                if (response.status == 200) {
                                    _this.meObject.evaluationPeriodId = response.data.id
                                    _this.sendDataFinalNotes()
                                }
                                _this.$bus.$emit('i-can-save-note-' + keyRef, response.data.id)
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }

                },


            sendDataFinalNotes() {

                let data = {
                    value: this.meObject.value.toFixed(2),
                    overcoming: null,
                    evaluation_periods_id: this.meObject.evaluationPeriodId,
                }
                axios.post('/teacher/evaluation/storeFinalNotes', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            //console.log(response.data)
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getConexion(){
                this.$store.dispatch('verifyConexion')
                if(!this.$store.state.isConexion){
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
    .error-conexion{
        background-color: #e0e2e5;
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
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }


</style>