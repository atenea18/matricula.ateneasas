<template>
    <div>
        <label for="">Seleccionar {{type.name}}</label>
        <multiselect 
                v-model="value"
                :options="data"
                :multiple="isMultiple"
                track-by="name"
                :custom-label="customLabel">
        </multiselect>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import {mapState, mapMutations, mapGetters } from 'vuex';

    export default {
        name: "multi-select",
        props: {
            data: {type: Array},
            type: {type: Object}
        },
        computed:{
            ...mapState(['asignatures']),
        },
        components: {Multiselect},
        data(){
            return{
                value: [],
                options: [],
                isMultiple: true,
            }
        },
        methods:{
            customLabel(option) {
                return `${option.name}`;
            },
        },
        created(){

        }
    }
</script>

<style scoped>

</style>