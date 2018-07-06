<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered" v-if="state">
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
                            <tr v-if="objectToStatsRating.enrollments" v-for="(enrollment,i) in objectToStatsRating.enrollments">
                                <td>
                                    {{i+1}}
                                </td>
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
                    enrollments: [],
                    data:{}
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
                this.$bus.$on('get-data-manager-group-select', object => {
                    this.objectToStatsRating.data = object.dataManagerGroupSelect
                    if (object.typeViewSection == 'stats-rating') {
                        this.getPositions(object.dataManagerGroupSelect)
                    }
                })
                this.$bus.$on('get-data-manager-group-select-change-section', object => {
                    if(object == 'stats-rating'){
                        this.getPositions(this.objectToStatsRating.data)
                    }
                })
            },
            getPositions(object) {
                this.state = false

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
                    this.state = true
                })

            },
        }
    }
</script>

<style scoped>

</style>