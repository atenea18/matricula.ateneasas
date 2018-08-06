<template>
    <div>

        <template v-if="objectConfigInstitution.status">
            <h5>Desempeños seleccionados</h5>
            <span class="performances" v-for="(data, index) in mainComponentObject.data" data-toggle="tooltip"
                  data-placement="bottom"
                  :title="data.name" @click="deleteRelation(data.id, index)">
                {{data.performances_id}}
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
                this.$store.state.parameters.forEach(element => {
                    this.$bus.$on("" + element.id, performance => {
                        this.mainComponentObject.performances_id = performance.id
                        this.storeRalation()
                    })
                })
            },

            getRelation() {
                let params = {
                    config: this.configComponent,
                    period_id: this.mainComponentObject.period_id,
                    group_pensum_id: this.mainComponentObject.group_pensum_id,
                }

                axios.get('/ajax/relation-performances/get', {params}).then(res => {
                    let data = res.data
                    this.mainComponentObject.data = res.data
                    if (data.length != 0) {
                        this.mainComponentObject.data = data
                    }

                })
            },

            storeRalation() {
                let data = {
                    config: this.configComponent,
                    period_id: this.mainComponentObject.period_id,
                    performances_id: this.mainComponentObject.performances_id,
                    group_pensum_id: this.mainComponentObject.group_pensum_id,
                }

                let _this = this
                axios.post('/ajax/relation-performances/store', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            if (response.data.id != 0) {
                                _this.mainComponentObject.data.push(response.data)
                            }
                        }
                    }).catch(function (error) {
                    //console.log(error);
                });
                //console.log(data)
            },

            deleteRelation(group_performances_id, index) {
                let data = {
                    config: this.configComponent,
                    group_performances_id: group_performances_id,
                }

                let _this = this
                axios.post('/ajax/relation-performances/delete', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            if(response.data == 1)
                                _this.mainComponentObject.data.splice(index,1)
                        }
                    })
                    .catch(function (error) {
                        //console.log(error);
                    });
            }
        },

    }
</script>

<style>
    .performances {
        padding: 6px;
        margin: 4px;
        background-color: #eee;
    }

    .performances:hover {
        cursor: pointer;
        color: red;
    }
</style>