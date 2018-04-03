<template>
    <div>
        <input @keyup="writingNotes" :id="'input'+count" class="form-control" style="padding:2px 2px" type="text"
               v-model="valuenote"
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
                beforevalue: 0,
                count: 0
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
                this.sendDataNotes(keyEvaluationPeriodId)
            });


        },
        mounted() {
            this.count = this.$store.state.counterInput
            this.$store.state.counterInput++

        },

        computed: {
            ...mapState([
                'asignature',
                'periodSelected',
                'counterInput',
                'counterParameter',
                'totalInput'
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
            setFocusElement(nextInput){
                if(nextInput > 0 && nextInput <= this.$store.state.totalInput){
                    let element = document.getElementById('input' + nextInput)
                    element.focus()
                }
            },
            writingNotes(e) {
                if (this.beforevalue != this.valuenote) {
                    let referencia = this.refsInputEvaluation
                    this.$bus.$emit('set-dirty-' + referencia, this.refsr)
                    this.beforevalue = this.valuenote
                }
                //rigth
                if (e.keyCode == 39) {
                    let nextInput = this.count + 1
                    this.setFocusElement(nextInput)
                }

                //left
                if (e.keyCode == 37) {
                    let nextInput = this.count - 1
                    this.setFocusElement(nextInput)
                }
                //down
                if (e.keyCode == 40) {
                    let nextInput = this.count + this.$store.state.counterParameter
                    this.setFocusElement(nextInput)
                }
                //up
                if (e.keyCode == 38) {
                    let nextInput = this.count - this.$store.state.counterParameter
                    this.setFocusElement(nextInput)
                }
                

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