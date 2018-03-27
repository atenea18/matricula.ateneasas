<template>
    <tr>
        <td>{{setting.index+1}}</td>
        <td style="width:320px"> {{fullName}}</td>
        <td>
            <!--
            <label v-show="isConexion" for="">Si</label>
            <label v-show="!isConexion" for="">No</label>
            -->
        </td>
        <template v-for="parameter in parameters">
            <td v-for="note_parameter in parameter.notes_parameter">
                <input-evaluation
                        :ref="refsInputEvaluation+parameter.id"
                        :setting="setting" :noteparameter="note_parameter"
                        :parameter="parameter"></input-evaluation>
            </td>
            <input-parameter :ref="refsInputParameter" :setting="setting" :parameter="parameter"/>
        </template>
        <td style="padding-top:16px;width:15px">
            <label v-show="valuenote">{{valuenote.toFixed(2)}} </label>
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
            setting: {type: Object}
        },

        data() {
            return {
                enrollmentid: 0,
                isExistEvaluationPeriod: false,
                evaluationperiodid: 0,
                valuenote: 0,
                isSend: false,
                isConexion: true
            }
        },

        computed: {
            ...mapState(["parameters", "asignature", "periodSelected"]),

            fullName() {
                return this.setting.enrollment.student_last_name + " " + this.setting.enrollment.student_name
            },
            refsInputParameter() {
                return "" + this.setting.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected
            },
            refsInputEvaluation() {
                return "" + this.setting.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected
            },
            parametersAll() {
                return this.$store.state.parameters;
            }
        },

        created() {
            this.enrollmentid = this.setting.enrollment.id;
        },
        mounted() {
            this.eventForUpdateInputParameter();

            this.isSend = true;
            this.parameters.forEach(parameter => {
                let referencia = this.refsInputEvaluation + parameter.id
                let notesBelongToParameter = this.$refs[referencia];
                this.$bus.$emit("set-notes-to-parameter-" + referencia, notesBelongToParameter);
            })

            this.valuenote = 0;
            let notesParameters = this.$refs[this.refsInputParameter];
            notesParameters.forEach(e => {
                this.valuenote += e.value
            })


        },
        updated() {
            if (navigator.onLine) {
                this.isConexion = true
            } else {
                this.isConexion = false
            }

        },
        methods: {

            /*
             *  eventForUpdateInputParameter
             */
            eventForUpdateInputParameter() {


                this.parameters.forEach(parameter => {


                    parameter.notes_parameter.forEach(note_parameter => {
                        let referencia = this.refsInputEvaluation + parameter.id + note_parameter.id
                        this.$bus.$off("set-dirty-" + referencia)
                        this.$bus.$on("set-dirty-" + referencia, keyToSearch => {

                            let notesBelongToParameter = this.$refs[keyToSearch];
                            //console.log(notesBelongToParameter[0].valuenote)
                            this.$bus.$emit("set-notes-to-parameter-" + keyToSearch, notesBelongToParameter);

                            this.valuenote = 0;
                            let notesParameters = this.$refs[this.refsInputParameter];
                            notesParameters.forEach(e => {
                                this.valuenote += e.value
                            })
                            //Por cada evento keyup en cada input se ejecuta una sola vez el método para
                            //guardar una Evaluación de Periodo, esta retorna su id
                            this.sendDataEvaluationPeriods(referencia)
                        });
                    })


                });
            },

            sendDataEvaluationPeriods: _.debounce(function (keyRef) {

                    let data = {
                        enrollment_id: this.setting.enrollment.id,
                        periods_id: this.$store.state.periodSelected,
                        asignatures_id: this.$store.state.asignature.id
                    }
                    let _this = this

                    if (this.isSend) {
                        let nameEvent = '' +
                            _this.setting.enrollment.id +
                            _this.$store.state.asignature.id +
                            _this.$store.state.periodSelected

                        axios.post('/teacher/evaluation/storeEvaluationPeriods', {data})
                            .then(function (response) {

                                if (response.status == 200) {
                                    _this.evaluationperiodid = response.data.id
                                    _this.sendDataFinalNotes()
                                }
                                _this.$bus.$emit('i-can-save-note-' + keyRef, response.data.id)
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }

                },
                // Este es el número de milisegundos que esperamos
                // a que el usuario termine de tipear.
                800),


            sendDataFinalNotes() {

                let data = {
                    value: this.valuenote.toFixed(2),
                    overcoming: null,
                    evaluation_periods_id: this.evaluationperiodid,
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
            }


        }
    }
</script>

<style scoped>

</style>