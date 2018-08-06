<template>
    <div class="form-group padding-at">
        <label>{{title}}</label>
        <!-- Emit Event: -->
        <form-select v-if="objectForSelectForm.arrayData" :objectInput="objectForSelectForm"></form-select>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import FormSelect from "./Generic/Form/FormSelect";

    export default {
        components: {FormSelect},
        name: "select-subgroup",
        props: {
            /*
                referenceChangeFormSelect: 'get-event-change-of-form-select@'+this.identification+'.subgroup',
                referenceGetObjectSelected: 'get-object-selected@'+this.identification+'.subgroup',
                referenceEmitObjectGradeSelected: 'set-emit-object-grade-selected@'+this.identification+'.subgroup',
            */
            objectInput: {type: Object}
        },
        data() {
            return {
                title:"Seleccionar un Subgrupo",
                objectForSelectForm:{
                    arrayData: [],
                    referenceChangeFormSelect: this.objectInput.referenceChangeFormSelect
                }
            }
        },
        created() {
            this.managerEvents()
            //console.log("hola")
        },
        mounted() {

        },
        updated() {

        },
        computed: {

        },
        methods: {
            managerEvents() {
                this.$bus.$on(this.objectInput.referenceEmitObjectGradeSelected, objectGrade => {
                    this.getSubgroupsByGrade(objectGrade)
                })

                //Escucha el evento de change del componente form-select
                this.$bus.$on(this.objectInput.referenceChangeFormSelect, objectGrade => {
                    //Dispara el acción después del evento change con el objeto seleccionado
                    this.$bus.$emit(this.objectInput.referenceGetObjectSelected, objectGrade);
                })
            },
            getSubgroupsByGrade(objectGrade) {

                let params = {
                    grade_id: objectGrade.id
                }

                axios.get('/ajax/getSubgroupsByGrade', {params}).then(res => {
                    this.objectForSelectForm.arrayData = res.data;
                })
            }

        }
    }
</script>

<style scoped>

</style>