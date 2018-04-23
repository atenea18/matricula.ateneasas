<template>
    <div class="form-group padding-at">
        <label>{{title}}</label>
        <!-- Emit Event: -->
        <form-select :objectInput="objectToSelectGroup"></form-select>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import FormSelect from "./Generic/Form/FormSelect";

    export default {
        components: {FormSelect},
        name: "select-group",
        props: {
            /*
                referenceChangeFormSelect: 'get-event-change-of-form-select@"identificador"',
                referenceGetObjectSelected: 'get-object-selected-@"identificador"',
                referenceEventToListing: ''
             */
            objectInput: {type: Object}
        },
        data() {

            return {
                title: "Seleccionar Grupo",
                state:false,
                objectToSelectGroup:{
                    arrayData: [],
                    referenceChangeFormSelect: this.objectInput.referenceChangeFormSelect,
                }
            }
        },

        created() {
            this.managerEvents()
        },

        computed: {

        },

        methods: {
            managerEvents() {
                let referenceOnGetObjectSelected = this.objectInput.referenceChangeFormSelect
                let referenceEmitObjectSelected = this.objectInput.referenceGetObjectSelected
                let referenceToReciveObjectSelected = this.objectInput.referenceToReciveObjectSelected

                //Escucha el evento de change del componente form-select
                this.$bus.$on(referenceOnGetObjectSelected, object => {
                    //Dispara el acción después del evento change con el objeto seleccionado
                    this.$bus.$emit(referenceEmitObjectSelected, object);
                })

                this.$bus.$on(referenceToReciveObjectSelected, object => {
                    this.getGroupsByGrade(object)
                })
            },

            getGroupsByGrade(object) {

                let params = {grade_id: object.id}
                let url = '/ajax/getGroupsByGrade'

                axios.get(url, {params}).then(res => {
                    this.objectToSelectGroup.arrayData = res.data;
                    this.state = true
                })
            }
        },

        destroyed() {

            this.$bus.$off(this.objectInput.referenceChangeFormSelect);
            this.$bus.$off(this.objectInput.referenceGetObjectSelected);
        }
    }
</script>

<style scoped>

</style>