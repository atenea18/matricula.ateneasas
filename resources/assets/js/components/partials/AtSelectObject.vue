<template>
    <div class="form-group padding-at">

        <label>Seleccionar {{}}</label>

        <!--
        <select v-on:change="eventChange" class="form-control" name="" v-model="">
            <option :value="{}">Seleccionar</option>
            <option v-for="row in " :value="row">
                {{ row.name }}
            </option>
        </select>
        -->


        <!--
        <ul v-show="picked" class="content-error">
            <li v-for="error in errors">
                {{error.message}}
            </li>
        </ul>
        -->
    </div>
</template>

<script>
    export default {
        name: "at-select-object",
        components: {},
        props: {
        },
        data() {
            return {
                /* Recibe información del padre */
                inObjectSelect: {
                    nameSelect: 'Grado',
                    nameEvent: '',
                    nameDependencie: '',
                    isValidate: false,
                    idSelected: 0,
                    rows: []
                },
                /* Información del componente */
                outObjectSelect: {
                    selectedId: 0,
                    selectedRow: {}
                }
            }
        },
        created() {

        },
        mounted() {


        },
        updated() {

        },
        methods: {

            eventChange(){
                this.outObjectSelect.selectedId = this.outObjectSelect.selectedRow.id
            }
        }
    }
</script>

<style scoped>

</style>