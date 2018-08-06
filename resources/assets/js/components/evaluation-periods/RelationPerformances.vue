<template>
    <th @click="deleteRelation" style="width: 44px !important;">
        <a href="#" title="Eliminar">{{ mainComponentObject.performances_id?mainComponentObject.performances_id:''
            }}</a>
    </th>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "relation-performances",
        props: {
            objectInput: {type: Object}
        },
        computed: {
            ...mapState([
                'periodSelected',
                'institutionOfTeacher',
                'isConexion',
                'groupPensum',
                'configInstitution'

            ]),
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
                objectConfigInstitution: {
                    config_type_id: 1,
                    config_type_description: "define el tipo de relación de desempeño, si es por columna o por filas ",

                    config_options_id: 2,
                    config_options_name: "column",

                    config_name: "relation_performances",
                    status: false,
                },
                configComponent: [],
                mainComponentObject: {
                    data: null,
                    period_id: 0,
                    state: false,
                    performances_id: 0,
                    group_pensum_id: 0,
                    notes_parameters_id: 0,
                    notes_performances_id: 0,
                },

            }
        },

        created() {
            this.objectToParameter = this.objectInput

            this.mainComponentObject.notes_parameters_id = this.objectInput.id
            this.mainComponentObject.period_id = this.$store.state.periodSelected
            this.mainComponentObject.group_pensum_id = this.$store.state.groupPensum.id

        },
        mounted() {

            let config = this.$store.state.configInstitution.find(rowConfig => {
                return this.compareToRelationPerformances(rowConfig)
            })

            if (config) {
                this.configComponent.push({
                    id: config.config_type_id,
                    name: config.config_institution_name,

                    option_id: config.config_options_id,
                    option_name: config.config_options_name,
                })
                this.onEventSelectedPerformance()

                this.getRelation()
            }



        },

        methods: {
            compareToRelationPerformances(rowConfig) {
                if (rowConfig.config_type_id == this.objectConfigInstitution.config_type_id)
                    if (rowConfig.config_options_id == this.objectConfigInstitution.config_options_id)
                        return true
            },
            onEventSelectedPerformance() {
                this.objectConfigInstitution.status = true
                this.$bus.$off("" + this.objectToParameter.evaluation_parameter_id + this.objectToParameter.id)
                this.$bus.$on("" + this.objectToParameter.evaluation_parameter_id + this.objectToParameter.id, performances => {
                    this.mainComponentObject.performances_id = performances.id
                    this.storeRelation()
                })
            },

            getRelation() {

                let params = {
                    config: this.configComponent,
                    period_id: this.mainComponentObject.period_id,
                    group_pensum_id: this.mainComponentObject.group_pensum_id,
                    notes_parameters_id: this.mainComponentObject.notes_parameters_id,
                }

                axios.get('/ajax/relation-performances/get', {params}).then(res => {
                    let data = res.data
                    if (data.length != 0) {
                        this.mainComponentObject.notes_performances_id = data[0].id
                        this.mainComponentObject.performances_id = data[0].performances_id
                        this.mainComponentObject.state = true
                    }
                })
            },

            deleteRelation() {
                let data = {
                    config: this.configComponent,
                    notes_performances_id: this.mainComponentObject.notes_performances_id,
                }

                let _this = this
                axios.post('/ajax/relation-performances/delete', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            _this.mainComponentObject.state = false
                            _this.mainComponentObject.notes_performances_id = 0
                            _this.mainComponentObject.performances_id = 0
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            storeRelation() {
                let data = {
                    config: this.configComponent,
                    period_id: this.mainComponentObject.period_id,
                    performances_id: this.mainComponentObject.performances_id,
                    group_pensum_id: this.mainComponentObject.group_pensum_id,
                    notes_parameters_id: this.mainComponentObject.notes_parameters_id,
                }

                let _this = this
                axios.post('/ajax/relation-performances/store', {data})
                    .then(function (response) {

                        if (response.status == 200) {
                            _this.mainComponentObject.notes_performances_id = response.data.id
                            _this.mainComponentObject.state = true
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