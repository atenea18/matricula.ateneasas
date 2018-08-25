<template>
    <div>
        <table class="table table-bordered">
            <thead>
            <tr style="font-size: 11px">
                <th>#</th>
                <th>ACCIÓN</th>
                <th>CÓDIGO</th>
                <th>DESEMPEÑO</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="(performance, index) in performances">
                <td>
                    {{index+1}}
                </td>
                <th>
                    <button class="btn btn-success btn-group-sm" @click="selectPerformances(performance)">Seleccionar
                    </button>
                </th>
                <th>{{performance.id}}</th>
                <td>
                    {{performance.name}}
                </td>
            </tr>
            </tbody>

        </table>
    </div>
</template>

<script>
    export default {
        name: "table-performances",
        data() {
            return {
                performances: [],
                state_table: false,
                parameter:{
                    id:0
                }
            }
        },
        created() {

        },
        mounted() {
            this.$bus.$on("eventElementsSelected@SelectsPerformances", data => {
                this.parameter.id = data.parameter_selected.id

                let params = {
                    pensum_id: data.asignature_selected.pensum_id,
                    evaluation_parameters_id: data.parameter_selected.id,
                    periods_id: data.period_selected.id
                }
                this.searchPerformances(params)
            })

            this.$bus.$on("EventSavedPerformance@CreatePerformances", (data) => {
                
                let params = {
                    pensum_id: data.pensum_id,
                    evaluation_parameters_id: data.parameter_id,
                    periods_id: data.period_id
                }
                this.searchPerformances(params)
            })
        },
        methods: {
            searchPerformances(params) {
                axios.get('/ajax/evaluation-search-performances', {params}).then(res => {
                    this.performances = res.data;
                })
            },
            selectPerformances(performance) {
                this.$bus.$emit(`EventChoosePerformances:${this.parameter.id}@TablePerformances`, performance);
            }
        }
    }
</script>

<style scoped>

</style>