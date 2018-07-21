<template>
    <div>
        <select v-on:change="selected" class="form-control"
                v-if="objectSetting.isCreated" v-model="objectToSelect.objectSelectedId">
            <option>Seleccionar</option>
            <option v-for="row in objectInput.arrayData" :value="row.periods_id">
                {{ row.periods_name}}
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
                name:""
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
                    objectSelectedId: 1
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
                this.$bus.$emit(reference, this.objectToSelect.objectSelectedId);
            }
        }
    }
</script>

<style scoped>

</style>