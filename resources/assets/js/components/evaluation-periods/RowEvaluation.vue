<template>
    <tr>
        <td>{{setting.index+1}}</td>
        <td style="width:320px"> {{fullName}}</td>
        <td> 2</td>
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
            <label>{{valuenote.toFixed(2)}} </label>
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
                isSend: false
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
            /* Obtiene las prevaloraciones para generar valoración final */
            this.getInputsParameters();
            /* Calcula prevaloraciones al renderizar el componente */
            this.eventForUpdateInputParameter("set-dirty-initial", "set-refs");
            /* Calcula prevaloraciones al escuchar evento de keyup */
            this.eventForUpdateInputParameter("set-dirty", "set-refs");

        },
        mounted() {
            this.isSend = true
        },
        methods: {

            /*
             *  eventForUpdateInputParameter
             */
            eventForUpdateInputParameter(keyEvent, KeyEmit) {
                this.parameters.forEach(parameter => {
                    let nameEvent = "" +
                        this.setting.enrollment.id +
                        this.$store.state.asignature.id +
                        this.$store.state.periodSelected +
                        parameter.id;

                    this.$bus.$off(keyEvent + "-" + nameEvent);
                    this.$bus.$on(keyEvent + "-" + nameEvent, pthis => {
                        let arraychilds = this.$refs[pthis];
                        this.$bus.$emit(KeyEmit + "-" + nameEvent, arraychilds);
                    });
                });
            },

            /*
             *  getInputsParameters
             */
            getInputsParameters() {
                let nameRef =
                    "" +
                    this.setting.enrollment.id +
                    this.$store.state.asignature.id +
                    this.$store.state.periodSelected;

                this.$bus.$off("set-note-" + nameRef);
                this.$bus.$on("set-note-" + nameRef, keyName => {
                    let arraychilds = this.$refs[keyName];
                    if (typeof arraychilds == "object") {
                        this.valuenote = 0;
                        arraychilds.forEach(e => {
                            this.valuenote += e.value;
                        });
                    }
                    if(this.evaluationperiodid == 0){
                        this.sendDataEvaluationPeriods()
                    }else{
                        let nameEvent = '' +
                            this.setting.enrollment.id +
                            this.$store.state.asignature.id +
                            this.$store.state.periodSelected
                        this.$bus.$emit("set-store-note-" + nameEvent, this.evaluationperiodid);
                    }

                });


            },

            sendDataEvaluationPeriods() {

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
                                _this.$bus.$emit("set-store-note-" + nameEvent, _this.evaluationperiodid);
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }

            },
            sendDataFinalNotes() {
                let data = {
                    value: this.valuenote.toFixed(2),
                    overcoming: null,
                    evaluation_periods_id: this.evaluationperiodid,
                }
                axios.post('/teacher/evaluation/storeFinalNotes', {data})
                    .then(function (response) {
                        if (response.status == 200) {
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