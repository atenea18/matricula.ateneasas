<template>
    <th style="width: 44px !important;">
        <a href="#" title="Eliminar">
            {{ mainComponentObject.performances_id? mainComponentObject.performances_id:''}}
        </a>
    </th>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "relation-performances",
        props: ['props-data'],
        computed: {
            ...mapState([
                'configInstitution',
                'stateEvaluation'
            ]),
        },
        data() {
            return {
                note_parameter: this.propsData.note_parameter,
                objectConfigInstitution: {
                    config_type_id: 1,
                    config_type_description: "define el tipo de relación de desempeño, si es por columna o por filas ",

                    config_options_id: 2,
                    config_options_name: "column",

                    config_institution_name: "relation_performances",
                    status: false,
                },
                configComponent: [],
                group_pensum_selected: {
                    id: this.$store.state.stateEvaluation.asignature_selected.info.group_pensum_id
                },
                period_selected: {
                    id: this.$store.state.stateEvaluation.period_selected.id
                },
                mainComponentObject:{
                    notes_performances_id:0,
                    performances_id: 0,
                    state:false,
                }
            }
        },
        watch: {},
        created() {
            this.$store.state.configInstitution.forEach(rowConfig => {
                this.compareToRelationPerformances(rowConfig)
            })

            if (this.objectConfigInstitution) {
                //Cada vez que se ejecuta este bloque de código, configComponent queda vació par
                //ser lleno nuevamente
                this.configComponent = []
                this.configComponent.push({
                    id: this.objectConfigInstitution.config_type_id,
                    name: this.objectConfigInstitution.config_institution_name,

                    option_id: this.objectConfigInstitution.config_options_id,
                    option_name: this.objectConfigInstitution.config_options_name,
                })
                //this.onEventSelectedPerformance()

                this.getRelation()
            }
        },
        methods: {
            compareToRelationPerformances(rowConfig) {
                if (rowConfig.config_type_id) {
                    if (rowConfig.config_type_id == this.objectConfigInstitution.config_type_id)
                    // Si es igual a row, objectConfigInstitution es igual a null
                    // mientras sea distinto de row, por defecto la configuracion es column
                        if (rowConfig.config_options_id == 1)
                            this.objectConfigInstitution = null
                }
            },
            getRelation() {
                let params = {
                    config: this.configComponent,
                    period_id: this.period_selected.id,
                    group_pensum_id: this.group_pensum_selected.id,
                    notes_parameters_id: this.note_parameter.id,
                }

                axios.get('/ajax/relation-performances/get', {params}).then(res => {
                    let data = res.data
                    if (data.length != 0) {
                        this.mainComponentObject.notes_performances_id = data.id
                        this.mainComponentObject.performances_id = data.performances_id
                        this.mainComponentObject.state = true
                    }
                })
            }
        }

    }
</script>

<style scoped>

</style>