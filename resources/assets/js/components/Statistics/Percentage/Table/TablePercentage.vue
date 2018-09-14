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

            <template v-for="subject in propsData.content_percentages">

                <template v-for="(period_subject, index) in subject.vectorPeriods">
                    <row-percentage
                            v-if="!propsData.options_selected.filter.isAcumulatedPeriod && period_subject.period_id==propsData.options_selected.objectValuesManagerGroupSelect.periods_id"
                            :props-data="{
                                subject:subject,
                                period_subject: period_subject,
                                titles_percentages: propsData.titles_percentages,
                                period_id_selected: propsData.options_selected.objectValuesManagerGroupSelect.periods_id,
                                period_id_subject: period_subject.period_id,
                                is_accumulated: propsData.options_selected.filter.isAcumulatedPeriod,
                                index: 0
                            }"/>

                    <row-percentage
                            v-if="propsData.options_selected.filter.isAcumulatedPeriod"
                            :props-data="{
                                subject:subject,
                                period_subject: period_subject,
                                titles_percentages: propsData.titles_percentages,
                                period_id_selected: period_subject.period_id,
                                period_id_subject: period_subject.period_id,
                                is_accumulated: propsData.options_selected.filter.isAcumulatedPeriod,
                                index: index
                            }"/>
                </template>
            </template>

            </tbody>
        </table>
    </div>
</template>

<script>
    import RowPercentage from "./RowPercentage";

    export default {
        components: {RowPercentage},
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

