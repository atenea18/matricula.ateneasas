<template>
    <div>
        <input @keyup="writingNotes" class="form-control" style="padding:2px 2px" type="text" v-model="valuenote"
        >
    </div>

</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "input-evaluation",
        props: {
            objectInput: {type: Object},
            noteparameter: {type: Object},
            parameter: {type: Object}
        },
        data() {
            return {
                valuenote: 0,
                evaluationperiodid: 0,
                percent: "",
                beforevalue: 0
            }
        },
        created() {


            this.percent = parseFloat(this.noteparameter.percent)
            this.valuenote = parseFloat(this.valuenote).toFixed(2)
            this.search(this.noteparameter.id)

            let referencia = this.refsInputEvaluation

            this.$bus.$off("i-can-save-note-" + referencia);
            this.$bus.$on("i-can-save-note-" + referencia, keyEvaluationPeriodId => {
                this.evaluationperiodid = keyEvaluationPeriodId
                //console.log(this.evaluationperiodid)
                this.sendDataNotes(keyEvaluationPeriodId)
            });


        },
        mounted() {

        },

        computed: {
            ...mapState([
                'asignature',
                'periodSelected'
            ]),
            refsInputParameter() {
                return "" + this.objectInput.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected + this.parameter.id + this.noteparameter.id
            },
            refsInputEvaluation() {
                return "" + this.objectInput.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected + this.parameter.id + this.noteparameter.id
            },
            refsr() {
                return "" + this.objectInput.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected + this.parameter.id
            },

        },
        methods: {
            writingNotes(e) {
                if (this.beforevalue != this.valuenote) {
                    let referencia = this.refsInputEvaluation
                    this.$bus.$emit('set-dirty-' + referencia, this.refsr)
                    this.beforevalue = this.valuenote
                }
                console.log(e)
                //
            },

            search(idnoteparameter) {
                let value = "";
                if (this.objectInput.enrollment.notes.length != 0) {
                    this.objectInput.enrollment.notes.forEach((note) => {
                        if (idnoteparameter == note.notes_parameters_id) {
                            value = note.value
                        }
                    })
                }
                this.valuenote = value
                this.beforevalue = value
            },

            sendDataNotes(key) {

                let data = {
                    value: this.valuenote,
                    overcoming: null,
                    evaluation_periods_id: key,
                    notes_parameters_id: this.noteparameter.id
                }
                //console.log(data)
                axios.post('/teacher/evaluation/storeNotes', {data})
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