<template>
    <td style="padding-top:6px !important;padding-left: 3px;padding-right: 2px; width:41px !important;">
        <label v-show="value">{{value.toFixed(1)}} </label>
    </td>
</template>

<script>
    import {mapState, mapMutations, mapGetters} from "vuex";

    export default {
        name: "input-parameter",

        props: {
            objectInput: {type: Object},
            parameter: {type: Object}
        },

        data() {
            return {
                value: 0,
                sumaZero: 0,
                percentWith: 0,
                percentZero: 0,
                promedioZero: 0,
                promedioWith: 0,
                countPercentZero: 0,
                objectToParameter: {
                    index: 0,
                    enrollment: {}
                }
            };
        },

        created() {
            this.objectToParameter = this.objectInput
        },

        computed: {
            ...mapState(["asignature", "periodSelected"]),

            refsInputEvaluation() {
                return "" + this.objectToParameter.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected + this.parameter.id
            },
        },

        mounted() {
            this.triggersEvents()
        },

        methods: {

            /*
             *  calculate
             */
            calculate(element) {

                //console.log(parseFloat(element.valuenote))
                //Si la nota no tiene porcentaje
                if (element.percent == 0) {
                    if (element.value > 0) {
                        this.sumaZero += element.value;
                        this.countPercentZero++;
                        //console.log(element.value)
                    }
                }

                //Si la nota si tiene porcentaje example proyecto 30%

                if (element.percent > 0) {
                    this.promedioWith += element.value * element.percent;
                    this.percentWith += element.percent;
                }
                //Se le asigna el porcentaje restante a las notas sin porcentajes
                this.percentZero = (this.parameter.percent / 100) - this.percentWith;
                if (this.countPercentZero != 0) {
                    this.promedioZero = (this.sumaZero / this.countPercentZero) * this.percentZero;
                }

            },

            /*
             *  calculateValoration
             */
            calculateValoration() {

                this.value = (this.promedioZero + this.promedioWith);
            },

            /*
             *  createEventSearchInputEvaluation
             */
            createEventSearchInputEvaluation() {
                let nameInputEvent =
                    "" +
                    this.objectToParameter.enrollment.id +
                    this.$store.state.asignature.id +
                    this.$store.state.periodSelected;
                this.$bus.$emit("set-note-" + nameInputEvent, nameInputEvent);
            },

            /*
             *  triggersEvents
             */
            triggersEvents() {
                let referencia = this.refsInputEvaluation

                this.$bus.$off("set-notes-to-parameter-" + referencia);
                this.$bus.$on("set-notes-to-parameter-" + referencia, (notes) => {
                    this.initial();
                    notes.forEach(note => {
                        let noteElement = {
                            value: parseFloat(note.valuenote) || 0,
                            percent: (parseFloat(note.percent)) / 100
                        }
                        //console.log(noteElement)
                        this.calculate(noteElement)
                    })
                    this.calculateValoration()
                })


            },

            /*
             *  initial
             */
            initial() {
                this.sumaZero = 0
                this.percentZero = 0
                this.percentWith = 0
                this.promedioZero = 0
                this.promedioWith = 0
                this.countPercentZero = 0
            }
        }


    };
</script>

<style scoped>

</style>