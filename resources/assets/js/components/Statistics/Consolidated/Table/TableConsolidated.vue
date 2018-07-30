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
                periodSelected: objectInput.params.objectValuesManagerGroupSelect.periods_id,
                }">
                </body-table-consolidated>
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
                    }">
                    </body-table-consolidated>
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

        <!--
        <vue-good-table
                :columns="columns"
                :rows="rows"
                class="table-custom"
                :searchOptions="{enabled: true,}"
                styleClass="vgt-table bordered condensed"
                :lineNumbers="true"
        >
        </vue-good-table>
            -->

        <!--
        <vue-good-table
                :columns="columns"
                :rows="rows"
                :groupOptions="{enabled: true}"
                :searchOptions="{enabled: true,}"
                styleClass="vgt-table bordered condensed"
        />
        -->
        <!--
        <div class="table-responsive">
            <table class="table table-bordered">

                <thead>
                <tr style="font-size: 11px;">
                    <th scope="col">No.</th>
                    <th>NOMBRES Y APELLIDOS</th>
                    <th>PER</th>
                    <th>TAV</th>
                    <th>PUESTO</th>
                    <th>PGG</th>
                    <th v-for="asignature in objectInput.asignatures">
                        {{asignature.abbreviation}}
                    </th>
                </tr>
                </thead>
                <tbody v-if="!objectInput.params.filter.isAcumulatedPeriod">
                <template v-for="(enrollment,i) in objectInput.enrollments">
                    <tr>
                        <td>{{i+1}}</td>
                        <td>{{fullname(enrollment)}}</td>
                        <td>{{objectInput.params.objectValuesManagerGroupSelect.periods_id}}</td>
                        <td>
                            {{(getTav(enrollment,objectInput.params.objectValuesManagerGroupSelect.periods_id)==0)?'':getTav(enrollment,objectInput.params.objectValuesManagerGroupSelect.periods_id)}}
                        </td>
                        <td>#</td>
                        <td>#..</td>
                        <td v-for="asignature in objectInput.asignatures">
                            <div v-html="getValueFinal(asignature,enrollment,objectInput.params.objectValuesManagerGroupSelect.periods_id)"></div>
                        </td>
                    </tr>
                </template>
                </tbody>
                <tbody v-else="!objectInput.params.filter.isAcumulatedPeriod">
                <template v-for="(enrollment,i) in objectInput.enrollments">
                    <tr>
                        <td rowspan="4">{{i+1}}</td>
                        <td rowspan="4">{{fullname(enrollment)}}</td>
                        <td> 1</td>
                        <td>{{(getTav(enrollment,1)==0)?'':getTav(enrollment,1)}}</td>
                        <td>#</td>
                        <td>#..</td>
                        <td v-for="asignature in objectInput.asignatures">
                            <div v-html="getValueFinal(asignature,enrollment,1)"></div>
                        </td>
                    </tr>
                    <template v-for="row in 3">
                        <tr>
                            <td> {{row+1}}</td>
                            <td>{{(getTav(enrollment,(row+1))==0)?'':getTav(enrollment,(row+1))}}</td>
                            <td>#</td>
                            <td>#..</td>
                            <td v-for="asignature in objectInput.asignatures">
                                <div v-html="getValueFinal(asignature,enrollment,(row+1))"></div>
                            </td>
                        </tr>
                    </template>
                    <tr>
                        <td></td>
                        <td colspan="3">PROMEDIO ACUMULADO</td>
                        <td rowspan="2">#</td>
                        <td rowspan="2">#..</td>
                        <td v-for="asignature in objectInput.asignatures">
                            <div v-html="'.'"></div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3">VALORACIÓN MIN. REQUERIDA PROX. PER.</td>

                        <td v-for="asignature in objectInput.asignatures">
                            <div v-html="'.'"></div>
                        </td>
                    </tr>
                    <tr style="font-size: 11px">
                        <th>No.</th>
                        <th>NOMBRES Y APELLIDOS</th>
                        <th>PER</th>
                        <th>TAV</th>
                        <th>PUESTO</th>
                        <th>PGG</th>
                        <th v-for="asignature in objectInput.asignatures">
                            {{asignature.abbreviation}}
                        </th>
                    </tr>

                </template>

                </tbody>

            </table>
        </div>
        -->
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
            this.$store.state.scaleEvaluation.forEach(element => {
                if (element.words_expressions_id == 4) {
                    this.$store.state.minScale = element.rank_end
                }
            })

            /*
            this.objectInput.enrollments.sort(function (a, b) {
                if (a.student_name > b.student_name) {
                    return 1;
                }
                if (a.student_name < b.student_name) {
                    return -1;
                }
                // a must be equal to b
                return 0;
            });
            */

            //console.log(this.objectInput.enrollments)

        },
        computed: {
            ...mapState([
                'periodsworkingday',
                'scaleEvaluation',
                'minScale'
            ]),

        },
        mounted() {

        },


        methods: {
            fullname(enrollment) {
                return enrollment.student_last_name + " " + enrollment.student_name
            },
            getAccumulated(enrollment, asignature) {
                let average = 0;
                enrollment.accumulatedSubjects.forEach(subjects => {
                    if (subjects.asignatures_id == asignature.asignatures_id) {
                        average = subjects.average.toFixed(2);
                    }
                })

                return average;
            },
            getRequired(enrollment, asignature) {
                let average = 0;
                enrollment.requiredValuation.forEach(subjects => {
                    if (subjects.asignatures_id == asignature.asignatures_id) {
                        average = subjects.required.toFixed(2);
                    }
                })

                return average;
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