<template>
    <thead>
    <tr style="font-size: 11px; background-color: #1d75b3; color: #FFFFFF">
        <th scope="col">No.</th>
        <th>NOMBRES Y APELLIDOS</th>
        <th>PER</th>
        <th>TAV</th>
        <th>PUESTO</th>
        <th>PGG</th>
        <th v-for="asignature in objectInput">
            <div data-toggle="tooltip" data-placement="top" :title="asignature.name">
                {{asignature.abbreviation}}
            </div>
        </th>
    </tr>
    </thead>
</template>

<script>
    export default {
        name: "head-table-consolidated",
        props:{
            objectInput: {type: Array}
        }
    }
</script>

<style scoped>

</style>