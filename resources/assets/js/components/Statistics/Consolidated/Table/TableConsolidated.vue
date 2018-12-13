<template>
    <div>
        <table class="table table-sm table-bordered">
            <head-table-consolidated :objectInput="objectInput.asignatures"></head-table-consolidated>
            <tbody>
            <!-- Por periodo-->
            <template v-for="(enrollment,i) in objectInput.enrollments"
                      v-if="!objectInput.params.filter.isAcumulatedPeriod">
                <body-table-consolidated :objectInput="{
                xrowspan:0,
                position:0,
                index:(i+1),
                enrollment:enrollment,
                options: objectInput.params,
                asignatures:objectInput.asignatures,
                isAcumulatedPeriod: objectInput.params.filter.isAcumulatedPeriod,
                isReprobated: objectInput.params.filter.isReprobated,
                periodSelected: objectInput.params.objectValuesManagerGroupSelect.periods_id,
                }"/>
            </template>

            <!-- Periodo Acumulado -->

            <template v-for="(enrollment,i) in objectInput.enrollments"
                      v-if="objectInput.params.filter.isAcumulatedPeriod">
                <template v-for="objectPeriod in periodsworkingday">
                    <body-table-consolidated :objectInput="{
                    index:(i+1),
                    enrollment:enrollment,
                    options: objectInput.params,
                    xrowspan: periodsworkingday.length,
                    asignatures:objectInput.asignatures,
                    periodSelected: objectPeriod.periods_id,
                    isAcumulatedPeriod: objectInput.params.filter.isAcumulatedPeriod,
                    isReprobated: objectInput.params.filter.isReprobated,
                    }"/>
                </template>

                <!-- Acumulados -->
                <tr style="background-color: rgb(247, 251, 254)">
                    <td style="text-align: left !important;" colspan="3">PROMEDIO ACUMULADO</td>
                    <td></td>
                    <td></td>
                    <td v-for="asignature in objectInput.asignatures">
                        <span v-html="getAccumulated(enrollment,asignature)"></span>

                    </td>
                </tr>
                <!-- Valoración Requerida -->
                <tr style="background-color: rgb(255, 253, 236); border-bottom: 1px solid #1d75b3 !important;">
                    <td style="text-align: left !important;" colspan="3">VALORACIÓN MIN. REQUERIDA PROX. PER.</td>
                    <td></td>
                    <td></td>
                    <td v-for="asignature in objectInput.asignatures">
                        <span v-html="getRequired(enrollment,asignature)"></span>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import HeadTableConsolidated from "./HeadTableConsolidated"
    import BodyTableConsolidated from "./BodyTableConsolidated"

    export default {
        components: {
            HeadTableConsolidated,
            BodyTableConsolidated
        },
        name: "table-consolidated",
        props: {
            objectInput: {type: Object}
        },

        data() {
            return {}
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
            fullname(enrollment) {
                return enrollment.student_last_name + " " + enrollment.student_name
            },
            getAccumulated(enrollment, asignature) {
                let average = 0;
                enrollment.accumulatedSubjects.forEach(subjects => {
                    if (subjects.asignatures_id == asignature.asignatures_id) {
                        average = subjects.average.toFixed(1);
                    }
                })
                if (average <= this.$store.state.minScale) {
                    return '<span style="color:red;">' + average + '</span>'
                }

                return average != 0 ? average : '';
            },
            getRequired(enrollment, asignature) {
                let average = 1000;
                enrollment.requiredValuation.forEach(subjects => {
                    if (subjects.asignatures_id == asignature.asignatures_id) {
                        average = subjects.required.toFixed(1);
                    }
                })
                if (average > this.$store.state.stateScale.max_scale) {
                    return '<span style="color:red;">REP</span>'
                }
                if (average < 0) {
                    return "APR"
                }
                return average != 0 ? average : '';
            },
        }
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