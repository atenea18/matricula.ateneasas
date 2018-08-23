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
        directives: {debounce},
        data() {
            return {
                debounceObject: {
                    delay: 2000
                },
                attendance: {
                    value: '',
                    is_send: true
                },
                displacement:{
                    counter: 0,
                    total_notes_parameters: 0,
                    total_inputs: 0,
                }
            }
        },
        created() {

        },
        mounted() {
            this.displacement.counter = this.$store.state.stateEvaluation.displacement.counter_input
            this.$store.state.stateEvaluation.displacement.counter_input++
        },
        computed: {
            ...mapState([
                'configInstitution',
                'stateEvaluation'
            ]),
        },
        methods: {

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
        }
    }
</script>

<style>
    .sendAttendance {
        background-color: #09c8f5;
    }

</style>