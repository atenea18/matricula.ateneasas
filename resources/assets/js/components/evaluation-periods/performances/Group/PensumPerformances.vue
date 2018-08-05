<template>
    <div>
        <template v-if="objectConfigInstitution.status">
            dd
            <span v-for="data in mainComponentObject.data">
                {{data.id}}
            </span>
        </template>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "pensum-performances",
        data() {
            return {
                objectConfigInstitution: {
                    config_type_id: 1,
                    config_type_description: "define el tipo de relación de desempeño, si es por columna o por filas ",

                    config_options_id: 1,
                    config_options_name: "row",

                    config_name: "relation_performances",
                    status: false,
                },
                configComponent: [],
                mainComponentObject: {
                    data: null,
                    period_id: 0,
                    performances_id: 0,
                    group_pensum_id: '',
                },

            }
        },
        created() {
            this.mainComponentObject.period_id = this.$store.state.periodSelected
            this.mainComponentObject.group_pensum_id = this.$store.state.groupPensum.id
        },
        computed: {
            ...mapState([
                'grade',
                'parameters',
                'periodSelected',
                'institutionOfTeacher',
                'isConexion',
                'groupPensum',
                'isTypeGroup',
                'configInstitution',
                'periodObjectSelected',
            ]),

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
                this.$store.state.parameters.forEach(element => {
                    this.$bus.$on("" + element.id, performance => {
                        this.mainComponentObject.performances_id = performance.id
                        this.storeGroupPensumPerformances()
                    })
                })
            },

            storeGroupPensumPerformances() {
                let data = {
                    config: this.configComponent,
                    period_id: this.mainComponentObject.period_id,
                    performances_id: this.mainComponentObject.performances_id,
                    group_pensum_id: this.mainComponentObject.group_pensum_id,
                }

                let _this = this
                axios.post('/teacher/evaluation/storeRelationPerformances', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            _this.mainComponentObject.data = response.data
                            console.log(response.data)
                        }
                    }).catch(function (error) {
                    console.log(error);
                });

                //console.log(data)
            }
        },

    }
</script>

<style scoped>

</style>