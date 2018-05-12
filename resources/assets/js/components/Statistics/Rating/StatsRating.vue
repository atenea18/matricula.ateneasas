<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <!-- Fila de titulos -->
                        <thead>
                        <tr style="font-size: 11px">
                            <th scope="col">No.</th>
                            <th> NOMBRES Y APELLIDOS</th>
                            <th> PUESTO </th>
                            <th>TAV</th>
                            <th>
                                PROMEDIO
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-if="objectToStatsRating.enrollments" v-for="enrollment in objectToStatsRating.enrollments">
                                <td></td>
                                <td>
                                    {{enrollment.last_name +" "+ enrollment.name }}
                                </td>
                                <td>
                                    {{enrollment.rating}}
                                </td>
                                <td>
                                    {{enrollment.tav}}
                                </td>
                                <td>
                                    {{enrollment.average}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "stats-rating",
        data() {
            return {
                objectToStatsRating: {
                    enrollments: []
                },
                state: false,
                data: {},
                urlPdf: ""
            }
        },
        created() {
            this.managerEvents()
        },
        methods: {
            managerEvents() {
                this.$bus.$on('spire', object => {
                    if (object.type == 'stats-rating') {
                        this.getPositions(object.fieldSelects)
                    }
                })
            },
            getPositions(object) {

                let params = {
                    grade_id: object.grade_id,
                    group_id: object.group_id,
                    periods_id: object.periods_id,
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    isSubGroup: object.isSubGroup
                }
                //console.log(this.data.isSubGroup)
                let url = '/ajax/getPositionStudents'

                axios.get(url, {params}).then(res => {
                    this.objectToStatsRating.enrollments = res.data
                })

            },
        }
    }
</script>

<style scoped>

</style>