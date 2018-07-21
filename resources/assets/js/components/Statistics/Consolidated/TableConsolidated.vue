<template>
    <div>
        <table class="table table-sm table-bordered">
            <head-table-consolidated :objectInput="objectInput.asignatures"></head-table-consolidated>
            <tbody>
            <template v-for="(enrollment,i) in objectInput.enrollments"
                      v-if="!objectInput.params.filter.isAcumulatedPeriod">
                <body-table-consolidated :objectInput="{
                enrollment:enrollment,
                asignatures:objectInput.asignatures,
                options: objectInput.params,
                periodSelected: objectInput.params.objectValuesManagerGroupSelect.periods_id,
                isAcumulatedPeriod: objectInput.params.filter.isAcumulatedPeriod,
                xrowspan:0,
                index:(i+1)
                }">
                </body-table-consolidated>
            </template>
            <template v-for="(enrollment,i) in objectInput.enrollments"
                      v-if="objectInput.params.filter.isAcumulatedPeriod">
                <template v-for="objectPeriod in periodsworkingday">
                    <body-table-consolidated :objectInput="{
                    enrollment:enrollment,
                    asignatures:objectInput.asignatures,
                    options: objectInput.params,
                    periodSelected: objectPeriod.periods_id,
                    isAcumulatedPeriod: objectInput.params.filter.isAcumulatedPeriod,
                    xrowspan: periodsworkingday.length,
                    index:(i+1)
                    }">
                    </body-table-consolidated>
                </template>

                <tr style="background-color: rgb(204, 225, 251)">
                    <td style="text-align: left !important;" colspan="3">PROMEDIO ACUMULADO</td>
                    <td></td>
                    <td></td>
                    <td v-for="asignature in objectInput.asignatures">

                    </td>
                </tr>
                <tr style="background-color: #fcf8e3">
                    <td style="text-align: left !important;" colspan="3">VALORACIÓN MIN. REQUERIDA PROX. PER.</td>
                    <td></td>
                    <td></td>
                    <td v-for="asignature in objectInput.asignatures">

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
    import RowTableConsolidated from "./RowTableConsolidated";
    import HeadTableConsolidated from "./Table/HeadTableConsolidated"
    import BodyTableConsolidated from "./Table/BodyTableConsolidated"

    export default {
        components: {
            RowTableConsolidated,
            HeadTableConsolidated,
            BodyTableConsolidated
        },
        name: "table-consolidated",
        props: {
            objectInput: {type: Object}
        },

        data() {
            return {


            }
        },

        created() {
            this.$store.state.scaleEvaluation.forEach(element =>{
                if(element.words_expressions_id == 4){
                    this.$store.state.minScale = element.rank_end
                }
            })
            //console.log(this.$store.state.scaleEvaluation)

            //console.log(this.objectInput.asignatures)
        },
        computed: {
            ...mapState([
                'periodsworkingday',
                'scaleEvaluation',
                'minScale'
            ]),

        },
        mounted() {

            /*
            this.$bus.$on('ya', object => {
                console.log("holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
            })

            this.$bus.$on('ya', object => {
                this.my(object.periods_id)
                console.log("holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
            })
            */
        },


        methods: {
            fullname(enrollment) {
                return enrollment.student_last_name + " " + enrollment.student_name
            },
            getValueFinal(asignature, enrollment, period) {

                let value = ""
                enrollment.notes_final.forEach((element, i) => {
                    if (element.asignatures_id == asignature.asignatures_id && element.value > 0 && element.periods_id == period) {
                        value = element.value.toFixed(1)
                        if (value <= 50) {
                            value = '<span style="color:red;">' + value + '</span>'
                        }
                        if (element.overcoming) {
                            let overcoming = element.overcoming
                            if (element.overcoming <= 50) {
                                overcoming = '<span style="color:red;">' + overcoming + '</span>'
                            }
                            value += "/" + overcoming
                        }

                    }
                })

                return value
            },

            my(periods_id) {
                this.columns = []
                this.rows = []
                this.columns = [

                    {
                        label: "NOMBRE COMPLETO",
                        field: "name"
                    },
                    {
                        label: "TAV",
                        field: "tav",
                        type: 'number',
                    },
                    {
                        label: "PGG",
                        field: "average"
                    }

                ]

                //Columnas
                this.objectInput.asignatures.forEach(element => {
                    let title = {
                        label: element.abbreviation,
                        field: "a" + element.asignatures_id,
                        html: true,
                    }
                    this.columns.push(title)
                })

                let students = []

                //Filas
                this.objectInput.enrollments.forEach((element, index) => {

                    let tav = 0
                    let sum = 0;
                    let average = 0;
                    let information = {
                        average: 0,
                        tav: 0
                    }
                    let content = {
                        id: (index + 1),
                        name: element.student_last_name + " " + element.student_name,
                        tav: tav,
                        average: 0,
                        position: 0
                    }


                    element.notes_final.forEach((el, i) => {

                        if (el.periods_id == periods_id) {


                            let valueNoteFinal = el.value.toFixed(1)
                            let valueNoteOvercoming = el.overcoming

                            if (valueNoteFinal > 0) {
                                tav += 1
                            }
                            if (valueNoteFinal >= valueNoteOvercoming) {
                                sum += parseFloat(valueNoteFinal)
                            } else {
                                sum += parseFloat(valueNoteOvercoming)
                            }

                            let value = el.value.toFixed(1)
                            if (value <= 50) {

                                value = '<span style="color:red;">' + value + '</span>'
                            }
                            if (el.overcoming) {
                                let overcoming = el.overcoming
                                if (el.overcoming <= 50) {
                                    overcoming = '<span style="color:red;">' + overcoming + '</span>'
                                }
                                value += "/" + overcoming
                            }
                            content['a' + el.asignatures_id] = value
                        }
                    })

                    if (tav > 0) {
                        average = sum / tav
                    }
                    content.average = average.toFixed(2)
                    content.tav = tav
                    information.average = average.toFixed(2)

                    students.push(information)
                    this.rows.push(content)
                })
                console.log(students)
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