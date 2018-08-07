<template>
    <tr>

        <template v-if="objectInput.isAcumulatedPeriod && objectInput.periodSelected == 1">
            <td style="text-align: left !important;" :rowspan="(objectInput.xrowspan+2)">{{objectInput.index}}</td>
            <td style="text-align: left !important;" :rowspan="objectInput.xrowspan"> {{fullname}}</td>
        </template>

        <template v-if="!objectInput.isAcumulatedPeriod">
            <td style="text-align: left !important;">{{objectInput.index}}</td>
            <td style="text-align: left !important;"> {{fullname}}</td>
        </template>
        <td data-toggle="tooltip" data-placement="top" title="PERIODO" style="background-color: rgb(247, 251, 254)">
            {{objectInput.periodSelected}}
        </td>
        <td data-toggle="tooltip" data-placement="top" title="TAV" style="background-color: rgb(247, 251, 254)">
            {{mainComponentObject.tav}}
        </td>
        <td data-toggle="tooltip" data-placement="top" title="PUESTO" style="background-color: rgb(247, 251, 254)">
            {{mainComponentObject.rating}}
        </td>
        <td data-toggle="tooltip" data-placement="top" title="PROMEDIO GENERAL"
            style="background-color: rgb(247, 251, 254)">{{mainComponentObject.average}}
        </td>
        <td v-for="asignature in objectInput.asignatures">
            <cell-notes :objectInput="{
            asignature: asignature,
            objectNote: asignmentNotes(asignature)
            }">
            </cell-notes>
        </td>
    </tr>
</template>

<script>
    import CellNotes from "./CellNotes"

    export default {
        name: "body-table-consolidated",
        components: {
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
                    rating: 0,
                    asignatures: []
                }
            }
        },
        computed: {
            fullname() {
                return this.objectInput.enrollment.student_last_name + " " + this.objectInput.enrollment.student_name
            },
        },

        created() {
            this.getNotes()
        },
        watch: {
            objectInput: function () {
                this.getNotes()
            },
        },
        methods: {
            asignmentNotes(asignature) {
                let note = 0
                if (this.mainComponentObject.asignatures.length) {
                    this.mainComponentObject.asignatures.forEach((element, i) => {
                        if (asignature.asignatures_id == element.id) {
                            note = element
                        }
                    })
                }
                return note
            },

            getNotes() {
                let evaluatedPeriods = this.objectInput.enrollment.evaluatedPeriods

                this.mainComponentObject.asignatures = []

                evaluatedPeriods.forEach(row => {

                    if (row.period_id == this.objectInput.periodSelected) {
                        this.mainComponentObject.tav = row.tav
                        this.mainComponentObject.rating = row.rating
                        this.mainComponentObject.average = row.average
                        row.notes.forEach(note => {
                            let valueNoteFinal = note.value != null ? (note.value.toFixed(1)) : 0
                            let valueNoteOvercoming = note.overcoming != null ? (note.overcoming.toFixed(1)) : 0

                            let asignature = {
                                id: note.asignatures_id,
                                value: valueNoteFinal,
                                overcoming: valueNoteOvercoming
                            }

                            this.mainComponentObject.asignatures.push(asignature)
                        })
                    }

                })
            },

        },

    }
</script>

<style scoped>

</style>