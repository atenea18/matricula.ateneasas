<template>
    <table class="table table-bordered">
        <thead>
        <tr style="font-size: 11px">
            <th>#</th>
            <th>DESEMPEÑO</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(performance, index) in performances">
            <td>
                {{index+1}}
            </td>
            <td>
                {{performance.name}}
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "table-performances",
        data(){
            return{
                performances: [],
                params:{}
            }
        },
        created(){
            this.$bus.$off("get-param-of-row-selects")
            this.$bus.$on("get-param-of-row-selects", params => {
                this.params = params
                this.searchPerformances(params)
            })

        },
        methods:{
            searchPerformances(params){
                axios.get('/teacher/evaluation/searchPerformances', {params}).then(res => {
                    this.performances = res.data;
                })
            }
        }
    }
</script>

<style>

</style>