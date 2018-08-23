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
        <!--
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
        -->
    </table>
</template>

<script>
    export default {
        name: "table-performances"
    }
</script>

<style scoped>

</style>