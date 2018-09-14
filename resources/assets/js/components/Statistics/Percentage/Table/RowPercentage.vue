<template>
    <tr>
        <template v-if="propsData.index == 0">
            <td style="text-align: left !important;"
                :rowspan="propsData.is_accumulated?propsData.subject.vectorPeriods.length:1">
                {{propsData.subject.name}}
            </td>
        </template>
        <td> {{propsData.period_id_selected}}</td>
        <td> {{propsData.period_subject.num_enrollment}}</td>
        <td> {{propsData.period_subject.num_enrollment>0?(propsData.period_subject.sum_value/propsData.period_subject.num_enrollment).toFixed(1):''}}</td>
        <td>
            <template v-for="scale in propsData.titles_percentages">
                <div v-if="(propsData.period_subject.sum_value/propsData.period_subject.num_enrollment).toFixed(1)>=scale.rank_start && (propsData.period_subject.sum_value/propsData.period_subject.num_enrollment).toFixed(1)<= scale.rank_end">
                    {{scale.name}}
                </div>
            </template>
        </td>
        <template v-for="scale in propsData.period_subject.vectorScales">
            <td style="width: 50px !important;">
                {{scale.counter>0?scale.counter:''}}
            </td>
            <td style="background-color: #f8f8f8;width: 60px !important;">
                <strong>{{scale.counter>0?((scale.counter/propsData.period_subject.num_enrollment)*100).toFixed(1)+'%':''}}</strong>
            </td>
        </template>
    </tr>
</template>

<script>
    export default {
        name: "row-percentage",
        props: ['props-data'],
    }
</script>

<style scoped>

</style>