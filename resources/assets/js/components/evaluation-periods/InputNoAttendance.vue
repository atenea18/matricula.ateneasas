<template>
    <td style="width:44px">

        <input v-debounce="delay" :class="isSend?'sendAttendance':'not-send'" @keypress="displacement"
               :id="'input'+count"
               class="form-controll"
               style="padding:2px 2px" type="text" v-model.lazy="quantity">
    </td>
</template>

<script>
    import debounce from '../../v-debounce/index'
    import {mapState} from 'vuex';

    export default {
        name: "input-no-attendance",
        props: {
            objectInput: {type: Object},
        },
        directives: {debounce},
        data() {
            return {
                quantity: '',
                beforeQuantity: '',
                percent: "",
                count: 0,
                isSend: true,
                delay: 1400,
                isFirst: true
            }
        },
        created() {
            this.quantity = this.objectInput.enrollment.no_attendance
            this.beforeQuantity = this.objectInput.enrollment.no_attendance

            let referencia = this.refEvaluationPeriodId

            this.$bus.$off("i-can-save-note-" + referencia);
            this.$bus.$on("i-can-save-note-" + referencia, keyEvaluationPeriodId => {
                this.sendDataAttendance(keyEvaluationPeriodId)
            });

            this.$bus.$off("no-attendance-saved" + referencia);
            this.$bus.$on("no-attendance-saved" + referencia, () => {
                this.isSend = true
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
            refEvaluationPeriodId() {
                return "" + this.objectInput.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected
            },

        },
        watch: {
            quantity: function () {
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
            style() {
                if (!this.isFirst) {
                    if (this.beforeQuantity != this.quantity) {
                        this.isSend = false
                    }
                }
            },

            writingNotes() {
                if (!this.isFirst) {
                    this.getConexion()
                    let val = parseFloat(this.quantity)
                    if (!isNaN(val)) {
                        this.sendEvent()
                    } else {
                        if (this.quantity == '') {
                            this.sendEvent()
                        }
                    }
                }
                this.isFirst = false;
            },

            sendEvent() {
                if (this.beforeQuantity != this.quantity && this.$store.state.isConexion) {

                    let referencia = this.refEvaluationPeriodId
                    this.$bus.$emit('dirty-input-no-attendance-' + referencia, this)
                    this.beforeQuantity = this.quantity

                }
            },

            getConexion() {
                this.$store.dispatch('verifyConexion')
            }
        }
    }
</script>

<style>
    .sendAttendance {
        background-color: #09c8f5;
    }

</style>