<template>
    <table class="table table-sm table-bordered">
        <thead>
        <tr>

            <th style="text-align: left !important;">Nombre de Estudiante</th>
            <th style="text-align: left !important;">Periodo</th>
            <th style="text-align: left !important;">Asignaturas</th>
            <th>Valoracion</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="(enrollment,key,index) of propsData.reprobated_enrollments" v-if="!propsData.is_accumulated">

            <template v-for="(period) of enrollment.evaluatedPeriods">
                <tr v-for="(note,key__,index__) of period.notes"
                    v-if="period.period_id == propsData.period_id_selected">
                    <td style="text-align: left !important;" v-if="index__==0 || key__==0" :rowspan="period.num_asignatures_reprobated">
                        {{enrollment.student_last_name+' '+enrollment.student_name}}
                    </td>
                    <td style="text-align: left !important;">
                        {{period.period_id}}
                    </td>
                    <td style="text-align: left !important;">
                        {{note.name_subjects}}
                    </td>
                    <td>
                        {{note.value}}
                    </td>
                </tr>
                <!--
                v-if="key_==0" :rowspan="enrollment.evaluatedPeriods.length"
                <tr v-for="note of period.notes" v-if="period.period_id == propsData.period_id_selected">
                    <td style="text-align: left !important;" v-if="getIsFirstRow(enrollment.id)"
                        :rowspan="period.num_asignatures_reprobated">

                        {{enrollment.student_last_name+' '+enrollment.student_name}}
                    </td>
                    <td style="text-align: left !important;">{{period.period_id}}</td>
                    <td style="text-align: left !important;">
                        {{note.name_subjects}}
                    </td>
                    <td>
                        {{note.value}}
                    </td>
                </tr>
                -->
            </template>
        </template>
        <template v-for="(enrollment,key,index) of propsData.reprobated_enrollments" v-if="propsData.is_accumulated">
            <template v-for="(period,key_,index_) of enrollment.evaluatedPeriods">
                <tr v-for="(note,key__,index__) of period.notes" >
                    <td v-if="index__==0 || key__==0" :rowspan="period.num_asignatures_reprobated">
                        {{enrollment.student_last_name+' '+enrollment.student_name}}
                    </td>
                    <td>
                        {{period.period_id}}
                    </td>
                    <td style="text-align: left !important;">
                        {{note.name_subjects}}
                    </td>
                    <td>
                        {{note.value}}
                    </td>
                </tr>
                <!--
                v-if="(index__==0 || key__==0)&& key_==0" :rowspan="enrollment.total_asignatures_reprobated"

                :rowspan="enrollment.total_asignatures_reprobated"
                v-if="index__==0 || key__==0" :rowspan="period.num_asignatures_reprobated"

                v-if="key_==0" :rowspan="enrollment.evaluatedPeriods.length"
                <tr v-for="note of period.notes" v-if="period.period_id == propsData.period_id_selected">
                    <td style="text-align: left !important;" v-if="getIsFirstRow(enrollment.id)"
                        :rowspan="period.num_asignatures_reprobated">

                        {{enrollment.student_last_name+' '+enrollment.student_name}}
                    </td>
                    <td style="text-align: left !important;">{{period.period_id}}</td>
                    <td style="text-align: left !important;">
                        {{note.name_subjects}}
                    </td>
                    <td>
                        {{note.value}}
                    </td>
                </tr>
                -->
            </template>
        </template>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "table-reprobated",
        props: ['props-data'],
        data() {
            return {
                enrollment: {
                    counter: 0,
                    id: 0,

                },

            }
        },

        methods: {
            getIsFirstRow(enrollment_id) {
                if (this.enrollment.id != enrollment_id) {
                    this.enrollment.id = enrollment_id
                    return true
                } else {
                    return false
                }
            },
        }
    }
</script>

<style scoped>

</style>