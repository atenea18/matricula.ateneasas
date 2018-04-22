<template>
    <div>
        <input v-debounce="delay" :class="isSend?'send':'not-send'" @keypress="displacement" :id="'input'+count"
               class="form-controll"
               style="padding:2px 2px" type="text" v-model.lazy="valuenote">
    </div>

</template>

<script>
    import debounce from '../../v-debounce/index'
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
                valuenote: '',
                evaluationperiodid: 0,
                percent: "",
                beforevalue: '',
                count: 0,
                isSend: true,
                delay: 2000,
                isFirst: true
            }
        },
        directives: {debounce},
        created() {

            this.percent = parseFloat(this.noteparameter.percent)
            this.valuenote = ''
            this.search(this.noteparameter.id)

            let referencia = this.refsInputEvaluation

            this.$bus.$off("i-can-save-note-" + referencia);
            this.$bus.$on("i-can-save-note-" + referencia, keyEvaluationPeriodId => {
                this.evaluationperiodid = keyEvaluationPeriodId
                this.sendDataNotes(keyEvaluationPeriodId)
            });


        },
        updated() {
            this.isFirst = false
        },
        mounted() {
            this.count = this.$store.state.counterInput
            this.$store.state.counterInput++

        },

        computed: {
            ...mapState([
                'asignature',
                'periodSelected',
                'isConexion',
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
            refEvaluationPeriodsMoreParameter() {
                return "" + this.objectInput.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected + this.parameter.id
            },

        },
        watch: {
            valuenote: function () {
                this.style()
                this.writingNotes()
            }
        },
        methods: {
            setFocusElement(nextInput) {
                if (nextInput > 0 && nextInput <= this.$store.state.totalInput) {
                    let element = document.getElementById('input' + nextInput)
                    element.focus()
                }
            },
            displacement(e) {
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
                    let nextInput = this.count + this.$store.state.counterParameter + 1
                    this.setFocusElement(nextInput)
                }
                //up
                if (e.keyCode == 38) {
                    let nextInput = this.count - this.$store.state.counterParameter - 1
                    this.setFocusElement(nextInput)
                }
                //this.writingNotes()
                //console.log(e)

            },

            style(){
                if (!this.isFirst) {
                    if (this.beforevalue != this.valuenote) {
                        this.isSend = false
                    }
                }
            },
            writingNotes() {
                this.getConexion()
                let val = parseFloat(this.valuenote)
                if (!isNaN(val)) {
                    this.sendEvent()
                } else {
                    if (this.valuenote == '') {
                        this.sendEvent()
                    }

                }
            },

            sendEvent() {
                if (this.beforevalue != this.valuenote && this.$store.state.isConexion) {
                    let referencia = this.refsInputEvaluation
                    this.$bus.$emit('set-dirty-' + referencia, this.refEvaluationPeriodsMoreParameter)
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
                this.valuenote = value == null ? '' : value
                this.beforevalue = value == null ? '' : value
            },

            sendDataNotes(key) {

                let data = {
                    value: this.valuenote,
                    overcoming: null,
                    evaluation_periods_id: key,
                    notes_parameters_id: this.noteparameter.id
                }
                let _this = this
                axios.post('/teacher/evaluation/storeNotes', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            //console.log("guardo notas")
                            _this.isSend = true
                            _this.beforevalue = _this.valuenote
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getConexion() {
                this.$store.dispatch('verifyConexion')
            }

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