<template>
    <div class="col-md-12">
        <hr>
        <div class="form-group">
            <div class="checkbox">
                <button type="button" class="btn btn-success" @click="setEstorePerformances">Guardar Desempeño</button>
                <label style="margin-left: 30px;">
                    <input type="checkbox" v-model="objectCreate.isCopy"> Deseas desactivar copiado automático?
                </label>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Superior</label>
                    <textarea id="higher" class="form-control" rows="3" @keyup="setValues"
                              v-model="objectCreate.textHigher"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Alto</label>
                    <textarea ref="textarea" class="form-control" rows="3" @keyup="setValues"
                              v-model="objectCreate.textHigh"></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Básico</label>
                    <textarea ref="textarea" class="form-control" rows="3" @keyup="setValues"
                              v-model="objectCreate.textBasic"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Recomendación de Mejoramiento</label>
                    <textarea class="form-control" rows="3" @keyup="setValues"
                              v-model="objectCreate.textRecommendationBasic"></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Bajo</label>
                    <textarea ref="textarea" class="form-control" rows="3" @keyup="setValues"
                              v-model="objectCreate.textLow"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Recomendación de Superación</label>
                    <textarea class="form-control" rows="3" @keyup="setValues"
                              v-model="objectCreate.textRecommendationLow"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
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
                }
            }
        },
        created() {

            this.$bus.$on("get-param-of-row-selects", params => {
                this.params = params
                //console.log(params)
            })
        },
        mounted(){

        },
        beforeDestroy() {
            this.$bus.$off("get-param-of-row-selects")
        },
        computed: {
            ...mapState([
                'institutionOfTeacher',
            ]),
        },
        methods: {
            clear(){
                this.objectCreate.textHigher = ""
                this.objectCreate.textRecommendationBasic = ""
                this.objectCreate.textRecommendationLow = ""
                this.objectCreate.textHigh = ""
                this.objectCreate.textBasic = ""
                this.objectCreate.textLow = ""

            },
            setValues(e) {
                this.objectCreate.textHigher = this.objectCreate.textHigher.toUpperCase()
                this.objectCreate.textRecommendationBasic = this.objectCreate.textRecommendationBasic.toUpperCase()
                this.objectCreate.textRecommendationLow = this.objectCreate.textRecommendationLow.toUpperCase()

                if (e.target.id == "higher" && !this.objectCreate.isCopy) {
                    this.objectCreate.textHigh = (e.target.value.length ? this.objectCreate.wordHigh : '') + " " + this.objectCreate.textHigher
                    this.objectCreate.textBasic = (e.target.value.length ? this.objectCreate.wordBasic : '') + " " + this.objectCreate.textHigher
                    this.objectCreate.textLow = (e.target.value.length ? this.objectCreate.wordLow : '') + " " + this.objectCreate.textHigher
                } else {
                    this.objectCreate.textHigh = this.objectCreate.textHigh.toUpperCase()
                    this.objectCreate.textBasic = this.objectCreate.textBasic.toUpperCase()
                    this.objectCreate.textLow = this.objectCreate.textLow.toUpperCase()
                }
            },
            setEstorePerformances() {
                let data = {
                    message: this.objectCreate,
                    institution: this.$store.state.institutionOfTeacher,
                    performances: this.params
                }
                let _this = this
                axios.post('/teacher/evaluation/storePerformances', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            if(response.data.id != 0){
                                _this.clear()
                                _this.$bus.$emit("reload-table-performances", _this.params)
                                _this.$swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'LISTO',
                                    showConfirmButton: false,
                                    timer: 2000
                                })

                            }else{
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
</script>

<style scoped>

</style>