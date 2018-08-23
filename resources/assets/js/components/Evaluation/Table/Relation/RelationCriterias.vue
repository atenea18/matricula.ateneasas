<template>
    <th class="criterias">
        <span v-if="propsData.note_parameter.criterias" data-toggle="tooltip" data-placement="bottom"
              :title="propsData.note_parameter.criterias.criterias_name">
            {{propsData.note_parameter.criterias.criterias_abbreviation || ""}}
        </span>
    </th>
</template>

<script>
    export default {
        name: "relation-criterias",
        props: ['props-data'],
    }
</script>

<style scoped>
    .criterias{
        width: 44px !important;
        font-size: 10px !important;
    }
</style>