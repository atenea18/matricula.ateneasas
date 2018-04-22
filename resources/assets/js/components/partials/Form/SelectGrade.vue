<template>
    <div class="form-group padding-at">
        <label>{{title}}</label>
        <!-- Emit Event: -->
        <form-select v-if="grades" :objectInput="objectForSelectGrades"></form-select>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import FormSelect from "./Generic/Form/FormSelect";

    export default {
        components: {FormSelect},
        name: "select-grade",
        props: {
            /*
                referenceChangeFormSelect: 'get-event-change-of-form-select@"identificador"',
                referenceGetObjectSelected 'get-object-grade-selected-@"identificador"'
             */
            objectInput: {type: Object}
        },

        data() {
            return { title: "Seleccionar Grado", }
        },

        created() {
            this.managerEvents()
        },

        computed: {
            ...mapState([
                'grades',
            ]),

            objectForSelectGrades() {
                return {
                    arrayData: this.$store.state.grades,
                    referenceChangeFormSelect: this.objectInput.referenceChangeFormSelect
                }
            }
        },

        methods: {
            managerEvents() {
                let referenceOnGetObjectSelected = this.objectInput.referenceChangeFormSelect
                let referenceEmitObjectSelected = this.objectInput.referenceGetObjectSelected

                //Escucha el evento de change del componente form-select
                this.$bus.$on(referenceOnGetObjectSelected, objectGrade => {
                    //Dispara el acción después del evento change con el objeto seleccionado
                    this.$bus.$emit(referenceEmitObjectSelected, objectGrade);
                })
            },
        }
    }
</script>
