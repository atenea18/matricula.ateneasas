<template>
    <table class="table table-sm table-bordered">
        <thead>
        <tr>

            <th style="text-align: left !important;">Nombre de Estudiante</th>
            <th style="text-align: left !important;">Periodo</th>
            <th style="text-align: left !important;">Asignaturas</th>
            <th>Valoracion</th>
            <th>Acumulado</th>
            <th>Requerido</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="(enrollment,key,index) of propsData.reprobated_enrollments" v-if="!propsData.is_accumulated">

            <template v-for="(period) of enrollment.evaluatedPeriods">
                <tr v-for="(note,key__,index__) of period.notes"
                    v-if="period.period_id == propsData.period_id_selected">
                    <td style="text-align: left !important;" v-if="index__==0 || key__==0"
                        :rowspan="period.num_asignatures_reprobated">
                        {{enrollment.student_last_name+' '+enrollment.student_name}}
                    </td>
                    <td style="text-align: left !important;">
                        {{period.period_id}}
                    </td>
                    <td style="text-align: left !important;">
                        {{note.name_subjects}}
                    </td>
                    <td>
                        {{note.value.toFixed(1)}}
                    </td>
                    <td v-for="subject of enrollment.accumulatedSubjects"
                        v-if="subject.asignatures_id==note.asignatures_id">
                        {{subject.average.toFixed(1)}}
                    </td>
                    <td v-for="subject of enrollment.requiredValuation"
                        v-if="subject.asignatures_id==note.asignatures_id">
                        <span v-html="getRequired(subject.required)"></span>
                    </td>
                </tr>
            </template>
        </template>
        <template v-for="(enrollment,key,index) of propsData.reprobated_enrollments" v-if="propsData.is_accumulated">
            <template v-for="(period,key_,index_) of enrollment.evaluatedPeriods">
                <tr v-for="(note,key__,index__) of period.notes">
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
                        {{note.value.toFixed(1)}}
                    </td>
                    <td v-for="subject of enrollment.accumulatedSubjects"
                        v-if="subject.asignatures_id==note.asignatures_id">
                        {{subject.average.toFixed(1)}}
                    </td>
                    <td v-for="subject of enrollment.requiredValuation"
                        v-if="subject.asignatures_id==note.asignatures_id">
                        <span v-html="getRequired(subject.required)"></span>
                    </td>

                </tr>
            </template>
        </template>
        </tbody>
    </table>
</template>

<script>
    import {mapState} from 'vuex'

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
        created() {
            this.$store.state.stateScale.scales.forEach(element => {
                if (element.words_expressions_id == 4) {
                    this.$store.state.minScale = element.rank_end
                    this.$store.state.stateScale.min_scale = element.rank_start
                }

                if (element.words_expressions_id == 1) {
                    this.$store.state.stateScale.max_scale = element.rank_end
                }
            })
        },
        computed: {
            ...mapState([
                'periodsworkingday',
                'stateScale',
                'minScale'
            ]),

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
            getRequired(average,) {
                if (average > this.$store.state.stateScale.max_scale) {
                    return '<span style="color:red;">REP</span>'
                }
                if (average < this.$store.state.stateScale.min_scale) {
                    return "APR"
                }
                return average != 0 ? average.toFixed(1) : '';
            },
        }
    }
</script>

<style scoped>

</style>