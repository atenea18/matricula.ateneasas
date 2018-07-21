<template>
    <tr>
        <template v-if="objectInput.isAcumulatedPeriod && objectInput.periodSelected == 1">
            <td style="text-align: left !important;" :rowspan="(objectInput.xrowspan+2)">{{objectInput.index}}</td>
            <td style="text-align: left !important;" :rowspan="objectInput.xrowspan"> {{fullname}}</td>
        </template>
        <template v-if="!objectInput.isAcumulatedPeriod">
            <td style="text-align: left !important;" >{{objectInput.index}}</td>
            <td style="text-align: left !important;"> {{fullname}}</td>
        </template>
        <td data-toggle="tooltip" data-placement="top" title="PERIODO">{{objectInput.periodSelected}}</td>
        <td data-toggle="tooltip" data-placement="top" title="TAV">
            {{mainComponentObject.tav}}</td>
        <td data-toggle="tooltip" data-placement="top" title="PUESTO">#</td>
        <td data-toggle="tooltip" data-placement="top" title="PROMEDIO GENERAL">{{mainComponentObject.average}}</td>
        <td v-for="asignature in objectInput.asignatures">
            <cell-notes :objectInput="{
            asignature: asignature,
            objectNote: note(asignature)
            }">
            </cell-notes>
        </td>
    </tr>
</template>

<script>
    import CellNotes from "./CellNotes"

    export default {
        name: "body-table-consolidated",
        components:{
          CellNotes
        },
        props: {
            objectInput: {type: Object},
            index: {type: Number}
        },
        data() {
            return {
                mainComponentObject: {
                    tav: 0,
                    average: 0,
                    asignatures: [ ]
                }
            }
        },
        computed: {
            fullname() {
                return this.objectInput.enrollment.student_last_name + " " + this.objectInput.enrollment.student_name
            },
        },

        created() {
            this.my()
        },
        watch:{
            objectInput: function () {
                this.my()
            },
        },
        methods: {
            note(asignature){
                let note = 0
                if(this.mainComponentObject.asignatures.length){
                    this.mainComponentObject.asignatures.forEach((element, i) => {
                        if(asignature.asignatures_id == element.id){
                            note =  element
                        }
                    })
                }
                return note
            },
            identifyAsignature(asignature){

                this.mainComponentObject.asignatures.forEach(element =>{
                    if(asignature.asignatures_id == element.id){
                        return element
                    }
                })

            },

            my(){
                this.mainComponentObject.asignatures = []
                let tav = 0
                let sum = 0
                let average = 0
                this.objectInput.enrollment.notes_final.forEach((element, i) => {

                    if (element.periods_id == this.objectInput.periodSelected) {
                        let valueNoteFinal = element.value.toFixed(1)
                        let valueNoteOvercoming = element.overcoming!=null?(element.overcoming.toFixed(1)):0

                        let asignature = {
                            id: element.asignatures_id,
                            value: valueNoteFinal,
                            overcoming: valueNoteOvercoming
                        }

                        if (valueNoteFinal > 0) {
                            tav += 1
                        }
                        if (valueNoteFinal >= valueNoteOvercoming) {
                            sum += parseFloat(valueNoteFinal)
                        } else {
                            sum += parseFloat(valueNoteOvercoming)
                        }

                        this.mainComponentObject.asignatures.push(asignature)
                    }
                })

                if (tav > 0) {
                    average = sum / tav
                }

                this.mainComponentObject.average = average.toFixed(2)
                this.mainComponentObject.tav = tav
            },

        },

    }
</script>

<style scoped>

</style>