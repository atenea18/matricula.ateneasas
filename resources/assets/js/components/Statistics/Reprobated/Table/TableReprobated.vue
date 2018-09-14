<template>
    <table class="table table-sm table-bordered">
        <thead>
        <tr>
            <th style="text-align: left !important;">No.</th>
            <th style="text-align: left !important;">Nombre de Estudiante</th>
            <th style="text-align: left !important;">Periodo</th>
            <th style="text-align: left !important;">Asignaturas</th>
            <th>Valoracion</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="period in propsData.reprobated_periods">
            <!--
            <template v-for="(enrollment,i) in period.enrollments"
                      v-if="!propsData.is_accumulated">
                <tr v-for="(asignature,j) in enrollment.asignatures" v-if="propsData.period_id_selected==period.periods_id">
                    <td style="text-align: left !important;" v-if="j==0"
                        :rowspan="enrollment.asignatures.length">
                        {{(i+1)}}
                    </td>
                    <td style="text-align: left !important;" v-if="j==0"
                        :rowspan="enrollment.asignatures.length">
                        {{enrollment.student_last_name+' '+enrollment.student_name}}
                    </td>
                    <td v-if="j==0" :rowspan="enrollment.asignatures.length">
                        {{period.periods_id}}
                    </td>
                    <td style="text-align: left !important;">{{asignature.name_subjects}}</td>
                    <td>{{asignature.value}}</td>
                </tr>
            </template>
            <template v-for="(enrollment,i) in period.enrollments"
                      v-if="propsData.is_accumulated">
                <tr v-for="(asignature,j) in enrollment.asignatures">
                    <td style="text-align: left !important;" v-if="j==0"
                        :rowspan="enrollment.asignatures.length">
                        {{(i+1)}}
                    </td>
                    <td style="text-align: left !important;" v-if="j==0"
                        :rowspan="enrollment.asignatures.length">
                        {{enrollment.student_last_name+' '+enrollment.student_name}}
                    </td>
                    <td v-if="j==0" :rowspan="enrollment.asignatures.length">
                        {{period.periods_id}}
                    </td>
                    <td style="text-align: left !important;">{{asignature.name_subjects}}</td>
                    <td>{{asignature.value}}</td>
                </tr>
            </template>
            -->
        </template>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "table-reprobated",
        props: ['props-data'],
    }
</script>

<style scoped>

</style>