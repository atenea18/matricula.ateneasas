<template>
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
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "table-performances",
        data() {
            return {
                performances: [],
                params: {}
            }
        },
        created() {
            this.$bus.$on("get-param-of-row-selects", params => {
                this.params = params
                this.searchPerformances(params)
            })
            this.$bus.$on("reload-table-performances", params => {
                this.params = params
                this.searchPerformances(params)
            })


        },
        beforeDestroy() {
            this.$bus.$off("get-param-of-row-selects")
        },
        computed: {
            ...
                mapState(
                    [
                        "parameters",
                        "asignature",
                        "periodSelected",
                        "configInstitution"
                    ]),
        },
        methods: {
            searchPerformances(params) {
                axios.get('/teacher/evaluation/searchPerformances', {params}).then(res => {
                    this.performances = res.data;
                })
            },
            selectPerformances(codePerformances) {
                this.$bus.$emit("" + this.params.evaluation_parameters_id, codePerformances);
            }
        }
    }
</script>

<style>

</style>