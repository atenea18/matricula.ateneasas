<template>
    <div>
        <select v-on:change="selected" class="form-control"
                v-if="objectSetting.isCreated" v-model="objectToSelect.objectSelected">
            <option :value="{}">Seleccionar</option>
            <option v-for="row in objectInput.arrayData" :value="row">
                {{ row.name }}
            </option>
        </select>
    </div>
</template>

<script>
    export default {
        name: "form-select",
        props: {
            /*
            {
                arrayData: [],
                referenceChangeFormSelect:""
            }
             */
            objectInput: {type: Object}
        },
        data() {
            return {
                objectSetting: {
                    isCreated: false,
                },
                objectToSelect: {
                    arrayData: [],
                    objectSelected: {}
                }
            }
        },
        created() {
            //Objetos inicializados
            this.objectSetting.isCreated = true
        },
        methods: {
            selected() {
                let reference = this.objectInput.referenceChangeFormSelect
                this.$bus.$emit(reference, this.objectToSelect.objectSelected);
            }
        }
    }
</script>

<style scoped>

</style>