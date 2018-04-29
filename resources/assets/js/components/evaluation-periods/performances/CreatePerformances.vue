<template>
    <div class="col-md-12">
        <hr>
        <div class="form-group">
            <div class="checkbox">
                <button type="button" class="btn btn-success" @click="setEstorePerformances">Guardar Desempeño</button>
                <label style="margin-left: 30px;">
                    <input type="checkbox" v-model="objectCreate.isCopy" @click="checkCopy"> Deseas desactivar copiado
                    automático?
                </label>
            </div>
        </div>
        <br>
        <div class="row">
            <template v-if="objectMessages" v-for="scale in scaleEvaluation">
                <scale-evaluation-column ref="levelPerformances" :objectInputMax="objectMessages"
                                         :objectInput="scale"></scale-evaluation-column>
            </template>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import ScaleEvaluationColumn from "./ScaleEvaluationColumn";

    export default {
        components: {ScaleEvaluationColumn},
        name: "create-performances",
        data() {
            return {
                params: {},
                objectCreate: {
                    textHigher: "",
                    textHigh: "",
                    textBasic: "",
                    textLow: "",
                    textRecommendationBasic: "",
                    textRecommendationLow: "",
                    wordHigh: "GENERALMENTE",
                    wordBasic: "ALGUNAS VECES",
                    wordLow: "POCAS VECES",
                    isCopy: false
                },
                objectMessages: {}
            }
        },
        created() {
            this.$bus.$on("get-param-of-row-selects", params => {
                this.params = params
            })

            //Identifica la escal superior
            let object = {}
            let max = 0
            this.$store.state.scaleEvaluation.map((element, i) => {
                if (element.rank_end > max) {
                    object = this.$store.state.scaleEvaluation[i]
                    max = element.rank_end
                }
            })

            this.objectMessages = object

        },
        mounted() {

        },
        beforeDestroy() {
            this.$bus.$off("get-param-of-row-selects")
        },
        computed: {
            ...mapState([
                'institutionOfTeacher',
                'scaleEvaluation'
            ]),
        },
        methods: {

            checkCopy() {
                let isCopy = !this.objectCreate.isCopy
                console.log(isCopy)
                this.$bus.$emit("is-event-copy", isCopy)
            },

            setEstorePerformances() {

                let textLevel = this.$refs.levelPerformances
                let dataText = []
                textLevel.forEach(element => {
                    dataText.push({
                        name: element.objectExpression.text,
                        recommendation: element.objectExpression.recommendation,
                        scale_id: element.objectExpression.scaleId,
                        perfor: element.objectExpression.isPerformances
                    })
                })

                let data = {
                    institution: this.$store.state.institutionOfTeacher,
                    performances: this.params,
                    data: dataText
                }


                if (this.params.pensum_id) {
                    let _this = this
                    axios.post('/teacher/evaluation/storePerformances', {data})
                        .then(function (response) {
                            if (response.status == 200) {
                                console.log(response.data)
                                if (response.data.id != 0) {
                                    _this.$bus.$emit("reload-table-performances", _this.params)
                                    _this.$swal({
                                        position: 'top-end',
                                        type: 'success',
                                        title: 'LISTO',
                                        showConfirmButton: false,
                                        timer: 2000
                                    })

                                } else {
                                    _this.$swal({
                                        position: 'top-end',
                                        type: 'error',
                                        title: 'Error',
                                        showConfirmButton: false,
                                        timer: 2000
                                    })
                                }

                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }



            }
        }
    }
</script>

<style scoped>

</style>