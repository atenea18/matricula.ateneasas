<template>
    <div class="col-md-12">
        <hr>
        <div class="form-group">
            <div class="checkbox">
                <button type="button" class="btn btn-success" @click="storePerformances">Guardar Desempeño</button>
                <label style="margin-left: 30px;">
                    <input type="checkbox" v-model="objectCreate.is_copy" @change="checkCopy"> Deseas desactivar copiado
                    automático?
                </label>
            </div>
        </div>
        <br>
        <div class="row">
            <div v-if="scale_higher" v-for="scale in stateScale.scales">
                <input-text :propsData="{scale:scale, scale_higher:scale_higher}" ref="InputTextLevelPerformances"/>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import InputText from "./InputText/InputText";

    export default {
        components: {InputText},
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
                    is_copy: false
                },
                parameter_selected: {
                    id: 0
                },
                period_selected: {
                    id: 0
                },
                pensum_selected: {
                    id: 0
                },
                objectMessages: null,
                scale_higher: null
            }
        },
        computed: {
            ...mapState([
                'configInstitution',
                'stateEvaluation',
                'stateInformation',
                'stateScale'
            ]),
        },
        created() {

        },
        mounted() {

            this.$bus.$on("eventElementsSelected@SelectsPerformances", data => {

                this.period_selected.id = data.period_selected.id
                this.pensum_selected.id = data.asignature_selected.pensum_id,
                this.parameter_selected.id = data.parameter_selected.id

                //Identifica la escala superior
                let scale_higher = 0
                let max = 0
                this.$store.state.stateScale.scales.map((scale, i) => {
                    if (scale.rank_end > max) {
                        scale_higher = this.$store.state.stateScale.scales[i]
                        max = scale.rank_end
                    }
                })

                this.scale_higher = scale_higher
            })
        },

        methods: {
            storePerformances() {
                let inputs_text = this.$refs['InputTextLevelPerformances']
                let vector_texts = []
                inputs_text.forEach(input_text => {
                    vector_texts.push({
                        name: input_text.scale_selected.text,
                        recommendation: input_text.scale_selected.recommendation,
                        scale_id: input_text.scale_selected.id,
                        is_higher: input_text.scale_selected.is_higher
                    })
                })

                let data = {
                    period_id: this.period_selected.id,
                    pensum_id: this.pensum_selected.id,
                    parameter_id: this.parameter_selected.id,
                    vector_texts: vector_texts
                }

                if (this.pensum_selected.id) {
                    let _this = this
                    axios.post('/ajax/evaluation-performances/store', {data})
                        .then(function (response) {
                            if (response.status == 200) {

                                if (response.data.id != 0) {
                                    _this.$bus.$emit("EventSavedPerformance@CreatePerformances", data)
                                    toastr.success('Guardado')
                                } else {
                                    toastr.error('Error al guardar')
                                }

                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },
            checkCopy() {

                this.$bus.$emit("EventChangeCopy@CreatePerformances", this.objectCreate.is_copy)
            }
        },
        destroyed() {
            this.$bus.$off("eventElementsSelected@SelectsPerformances")
        },


    }
</script>

<style>

</style>