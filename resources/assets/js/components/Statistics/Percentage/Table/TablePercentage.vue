<template>
    <div>
        <table class="table table-sm table-bordered">
            <thead>
            <tr>
                <th style="text-align: left !important;">NOMBRE ÁREAS O ASIGNATURAS</th>
                <th>PER</th>
                <th>TEV</th>
                <th>PROMEDIO</th>
                <th>DESEMPEÑO</th>
                <th style="width: 30px !important;" data-toggle="tooltip" data-placement="top" :title="scale.name"
                    colspan="2" v-for="scale in propsData.titles_percentages">
                    {{scale.abbreviation}}
                </th>

            </tr>
            </thead>
            <tbody>
            <tr v-for="subject in propsData.content_percentages">
                <td style="text-align: left !important;"> {{subject.name}}</td>
                <td> {{propsData.options_selected.objectValuesManagerGroupSelect.periods_id}}</td>
                <template v-for="period in subject.vectorPeriods"
                          v-if="period.period_id==propsData.options_selected.objectValuesManagerGroupSelect.periods_id">
                    <td> {{period.num_enrollment}}</td>
                    <td> {{period.num_enrollment>0?(period.sum_value/period.num_enrollment).toFixed(1):''}}</td>
                    <td>
                        <template v-for="scale in propsData.titles_percentages">
                            <div v-if="(period.sum_value/period.num_enrollment).toFixed(1)>=scale.rank_start && (period.sum_value/period.num_enrollment).toFixed(1)<= scale.rank_end">
                                {{scale.name}}
                            </div>
                        </template>
                    </td>
                    <template v-for="scale in period.vectorScales">
                        <td style="width: 50px !important;">
                            {{scale.counter>0?scale.counter:''}}
                        </td>
                        <td style="background-color: #f8f8f8;width: 60px !important;">
                            <strong>{{scale.counter>0?((scale.counter/period.num_enrollment)*100).toFixed(1)+'%':''}}</strong>
                            <!--
                            <strong>{{scale.counter>0?scale.percent_counter+'%':''}}</strong>
                            -->
                        </td>
                    </template>
                </template>

            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "table-percentage",
        props: ['props-data'],


    }
</script>

<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 3px;
        line-height: 1.42857143;
        vertical-align: middle;
        text-align: center;
        font-size: 12px !important;
        border-top: 1px solid #ddd;
    }
</style>