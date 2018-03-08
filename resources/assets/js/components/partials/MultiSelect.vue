<template>
    <div>
        <label for="">Seleccionar {{type.name}}</label>
        <multiselect @input="selected"
                v-model="value"
                :options="data"
                :multiple="isMultiple"
                track-by="name"
                :custom-label="customLabel">
        </multiselect>
        <ul v-show="picked" class="content-error">
            <li v-for="error in errors">
                {{error.message}}
            </li>
        </ul>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';

    export default {
        name: "multi-select",
        props: {
            data: {type: Array},
            type: {type: Object}
        },
        components: {Multiselect},
        data(){
            return{
                value: [],
                options: [],
                isMultiple: true,
                picked: false,
                errors: []
            }
        },
        computed:{

        },

        methods:{
            customLabel(option) {
                return `${option.name}`;
            },
            selected(){
                this.$bus.$emit('selected-values-'+this.type.nameEv, this.value)
            }
        },
        created(){

        }
    }
</script>

<style scoped>

</style>