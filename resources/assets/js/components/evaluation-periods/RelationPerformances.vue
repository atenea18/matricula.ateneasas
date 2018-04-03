<template>
    <th @click="deleteRelationPerformances">
        <a href="#" title="Eliminar">{{ meObject.codePerformance?meObject.codePerformance:'' }}</a>
    </th>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "relation-performances",
        props: {
            objectInput: {type: Object}
        },
        data() {
            return {
                objectToParameter: {
                    id: 0,
                    name: "",
                    percent: 0,
                    evaluation_parameter_id: 0,
                    notes_type_id: 0
                },
                meObject: {
                    id: 0,
                    state: false,
                    codePerformance: 0
                }
            }
        },
        created() {
            this.objectToParameter = this.objectInput
            this.getRelationPerformances()
        },
        mounted() {
            this.$bus.$off("" + this.objectToParameter.evaluation_parameter_id + this.objectToParameter.id)
            this.$bus.$on("" + this.objectToParameter.evaluation_parameter_id + this.objectToParameter.id, performances => {
                this.meObject.codePerformance = performances.id
                this.meObject.state = true
                this.storeRelationPerformances()
            })


        },
        computed: {
            ...mapState([
                'periodSelected',
                'institutionOfTeacher',
                'isConexion',
                'groupPensum'

            ]),
        },
        methods: {
            getRelationPerformances() {
                let params = {
                    notes_parameters_id: this.objectToParameter.id,
                    periods_id: this.$store.state.periodSelected,
                    group_pensum_id: this.$store.state.groupPensum.id
                }

                axios.get('/teacher/evaluation/getRelationPerformances', {params}).then(res => {
                    let data = res.data
                    if (data.length != 0) {
                        this.meObject.codePerformance = data[0].performances_id
                        this.meObject.id = data[0].id
                        this.meObject.state = true

                    }


                })
            },

            deleteRelationPerformances() {
                console.log(this.meObject.id)
            },
            storeRelationPerformances() {
                let data = {
                    notes_parameters_id: this.objectToParameter.id,
                    performances_id: this.meObject.codePerformance,
                    periods_id: this.$store.state.periodSelected,
                    group_pensum_id: this.$store.state.groupPensum.id
                }


                axios.post('/teacher/evaluation/storeRelationPerformances', {data})
                    .then(function (response) {

                        if (response.status == 200) {
                            this.meObject.id = response.data.id
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

            }
        }
    }

</script>

<style scoped>

</style>