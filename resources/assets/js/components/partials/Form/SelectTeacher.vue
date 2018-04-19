<template>
    <div class="form-group padding-at">
        <label>{{title}}</label>
        <!-- Emit Event: -->
        <form-select v-if="teachers" :objectInput="objectForSelectTeacher"></form-select>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import FormSelect from "./Generic/Form/FormSelect";

    export default {
        components: {FormSelect},
        name: "select-teacher",
        props: {
            /*
                referenceChangeFormSelect: 'get-event-change-of-form-select@"identificador"',
                referenceGetObjectSelected 'get-object-grade-selected-@"identificador"'
             */
            objectInput: {type: Object}
        },
        data() {
            return {
                title: "Seleccionar un Docente",
            }
        },
        created() {
            this.managerEvents()

        },
        computed: {
            ...mapState([
                'teachers',
            ]),

            objectForSelectTeacher() {
                return {
                    arrayData: this.$store.state.teachers,
                    referenceChangeFormSelect: this.objectInput.referenceChangeFormSelect
                }
            }
        },
        methods: {
            managerEvents() {
                let referenceOnGetObjectSelected = this.objectInput.referenceChangeFormSelect
                let referenceEmitObjectSelected = this.objectInput.referenceGetObjectSelected

                //Escucha el evento de change del componente form-select
                this.$bus.$on(referenceOnGetObjectSelected, object => {
                    //Dispara el acción después del evento change con el objeto seleccionado
                    this.$bus.$emit(referenceEmitObjectSelected, object);
                })
            },
        }
    }
</script>

<style scoped>

</style>