<template>
    <div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ objectInput.name }}</label>
                <textarea v-model="objectExpression.text" class="form-control" rows="3" @keyup="setValues"></textarea>
            </div>
        </div>
        <div class="col-md-6" v-if="objectInput.name_recommendation">
            <div class="form-group">
                <label class="control-label">{{objectInput.name_recommendation}}</label>
                <textarea v-model="objectExpression.recommendation" class="form-control" rows="3" @keyup="setValues"></textarea>
            </div>
        </div>


    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {

        name: "scale-evaluation-column",
        data(){
          return{
              objectExpression:{
                  text:"",
                  recommendation:"",
                  word: this.objectInput.words_expressions_name,
                  isCopy: false,
                  isPerformances: false,
                  scaleId:0
              }
          }
        },
        props: {
            objectInput: {type: Object},
            objectInputMax: {type:Object}
        },
        created(){
            this.objectExpression.scaleId = this.objectInput.id

            if(this.objectInputMax.id != this.objectInput.id){
                this.$bus.$on("get-text-superior", text =>{
                    this.objectExpression.text = ""
                    this.objectExpression.text = (text.length?this.objectExpression.word:"") +" "+text
                });
            }else{
                this.objectExpression.isPerformances = true
            }

            this.$bus.$on("is-event-copy",isCopy =>{
                this.objectExpression.isCopy = isCopy
            })

            //lo uso para saber si ya guardó los desempeños, pero el nombre del evento no le corresponde
            this.$bus.$on("reload-table-performances",f =>{
                this.objectExpression.text = ""
                this.objectExpression.recommendation = ""
            })
        },
        computed: {
            ...mapState([

            ]),
        },
        methods:{
            setValues(){
                this.objectExpression.text = this.objectExpression.text.toUpperCase()
                this.objectExpression.recommendation = this.objectExpression.recommendation.toUpperCase()
                if(this.objectInputMax.id == this.objectInput.id){
                    if(!this.objectExpression.isCopy){
                        let text = this.objectExpression.text
                        this.$bus.$emit("get-text-superior", text);
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>