<template>
    <table class="table table-bordered">
        <!-- Fila de titulos -->
        <thead>
        <tr style="font-size: 11px">
            <th scope="col">No.</th>
            <th> NOMBRES Y APELLIDOS</th>
            <th>TAV</th>
            <th v-for="asignature in objectInput.asignatures">
                {{asignature.abbreviation}}
            </th>
        </tr>
        </thead>
        <tbody>
        <template v-for="(enrollment,i) in objectInput.enrollments">
            <row-table-consolidated :objectInput="{
                enrollment:enrollment,
                asignatures:objectInput.asignatures,
                index:i
            }"></row-table-consolidated>
        </template>
        </tbody>
    </table>
</template>

<script>
    import RowTableConsolidated from "./RowTableConsolidated";

    export default {
        components: {RowTableConsolidated},
        name: "table-consolidated",
        props: {
            objectInput: {type: Object}
        }
    }
</script>

<style scoped>

</style>