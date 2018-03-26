<template>
    <div class="form-group padding-at">
        <label>Seleccionar {{inObject.name}}</label>

        <select v-on:change="eventChange" class="form-control" name="" v-model="outObject.selectedRow">
            <option :value="{}">Seleccionar</option>
            <option v-for="row in inObject.rows" :value="row">
                {{ row.name }}
            </option>
        </select>
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
            object: {type: Object}
        },
        data() {
            return {
                /* Recibe información del padre */
                inObject: {
                    nameSelect: 'Grado',
                    nameEvent: '',
                    nameDependencie: '',
                    isValidate: false,
                    idSelected: 0,
                    rows: []
                },
                /* Información del componente */
                outObject: {
                    selectedId: 0,
                    selectedRow: {}
                }
            }
        },
        created() {

        },
        mounted() {
            this.inObject = this.object
            //console.log(this.inObject.rows)
        },
        updated() {

        },
        methods: {

            eventChange(){
                this.outObject.selectedId = this.outObject.selectedRow.id
                console.log(this.outObject.selectedRow)
            }
        }
    }
</script>

<style scoped>

</style>