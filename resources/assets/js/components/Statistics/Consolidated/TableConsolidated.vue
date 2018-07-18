<template>
    <div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <!-- Fila de titulos -->
                <thead >
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
                        <td colspan="3">PROMEDIO ACUMULADO </td>
                        <td rowspan="2">#</td>
                        <td rowspan="2">#..</td>
                        <td v-for="asignature in objectInput.asignatures">
                            <div v-html="'.'"></div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3">VALORACIÓN MIN. REQUERIDA PROX. PER.</td>
                        <!--<td  >#</td>-->
                        <!--<td>#..</td>-->
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
                <!--
                <template v-for="(enrollment,i) in objectInput.enrollments">

                </template>
                -->
                </tbody>

            </table>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import RowTableConsolidated from "./RowTableConsolidated";

    export default {
        components: {RowTableConsolidated},
        name: "table-consolidated",
        props: {
            objectInput: {type: Object}
        },

        created() {
            //console.log(this.objectInput.params.objectValuesManagerGroupSelect)
        },
        computed: {
            ...mapState([
                'periodObjectSelected',
            ]),

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
                        if(value <= 50){
                            value = '<span style="color:red;">'+value+'</span>'
                        }
                        if (element.overcoming) {
                            let overcoming = element.overcoming
                            if(element.overcoming <= 50){
                                overcoming = '<span style="color:red;">'+overcoming+'</span>'
                            }
                            value += "/" + overcoming
                        }

                    }
                })

                return value
            },
            getTav(enrollment, period) {
                let count = 0
                enrollment.notes_final.forEach((element, i) => {
                    if (element.value != 0 && element.value != "" && element.periods_id == period) {
                        //console.log(element.value)
                        count++
                    }
                })
                count = count == 0 ? "" : count;
                return count
            }
        }
    }
</script>

<style scoped>


</style>