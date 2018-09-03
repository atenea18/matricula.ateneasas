<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <template v-if="state">
                    <table-percentage :props-data="{
                    titles_percentages: stateScale.scales,
                    content_percentages: mainComponent.percentages,
                    options_selected: mainComponent.params
                    }"/>
                    <!--
                    <table-consolidated id="table-consolidated"
                                        :objectInput="objectToTableConsolidated">
                    </table-consolidated>
                    -->
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import TablePercentage from "./Table/TablePercentage";

    export default {
        components: {TablePercentage},
        name: "main-percentage",
        computed: {
            ...mapState([
                'currentView',
                'stateScale',
            ]),
        },
        data() {
            return {
                state: false,
                params: {},
                mainComponent: {
                    params: {},
                    asignatures: [],
                    percentages: [],
                }
            }
        },
        created() {
            this.managerEvents()
        },
        methods: {
            managerEvents() {
                //Se subscribe al evento generado por menu-statistics, este le permite saber si se debe
                //mostrar la sección de consolidado con su respectiva consulta, ya que este evento devuelve
                //un objeto con los datos seleccionados de manager-group-select
                this.$bus.$off('SelectedFieldsEvent@MenuStatistics')
                this.$bus.$on('SelectedFieldsEvent@MenuStatistics', objectMenuStatistics => {

                    this.mainComponent.params = objectMenuStatistics

                    this.managerSelectedFieldsEvent(objectMenuStatistics)

                })

                // Se subscribe al evento generado por menu-statistics, para generar una nueva consulta de consolidados
                // si algún select de manager-group-select fue modificado

                this.$bus.$on('SelectedCurrentView@MenuStatistics', objectMenuStatistics => {
                    console.log(objectMenuStatistics)
                    this.mainComponent.params = objectMenuStatistics
                    this.managerSelectedFieldsEvent(this.mainComponent.params)
                })
            },
            managerSelectedFieldsEvent(objectMenuStatistics) {

                if (this.$store.state.currentView == 'main-percentage') {

                    this.getWhoTriggered(objectMenuStatistics)
                }
            },

            getWhoTriggered(objectMenuStatistics) {
                let whoTriggered = objectMenuStatistics.eventInformation.whoTriggered

                if (
                    whoTriggered == 'pdf' ||
                    whoTriggered == 'areas' ||
                    whoTriggered == 'excel' ||
                    whoTriggered == 'componentManagerGroupSelect') {
                    this.managerQueryForFilter(objectMenuStatistics)
                }
            },
            managerQueryForFilter(objectMenuStatistics) {

                let params = {
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    grade_id: objectMenuStatistics.objectValuesManagerGroupSelect.grade_id,
                    group_id: objectMenuStatistics.objectValuesManagerGroupSelect.group_id,
                    periods_id: objectMenuStatistics.objectValuesManagerGroupSelect.periods_id,

                    is_filter_areas: objectMenuStatistics.filter.isAreas,
                    is_filter_all_groups: objectMenuStatistics.filter.isAllGroups,
                    is_accumulated: objectMenuStatistics.filter.isAcumulatedPeriod,
                    is_subgroup: objectMenuStatistics.objectValuesManagerGroupSelect.isSubGroup,

                    url_subjects: '',
                    url_consolidated: '',
                    type_response: objectMenuStatistics.eventInformation.whoTriggered,
                }

                params.url_subjects = '/ajax/getSubjects'
                params.url_consolidated = '/ajax/getTablePercentage'

                this.getContent(params)
            },

            getContent(params) {
                // Petición para obetener la cabecera de la tabla (areas o asignaturas)
                axios.get(params.url_subjects, {params}).then(res => {
                    this.mainComponent.asignatures = res.data
                    //Si petición anterior es ok
                    this.dispatcher(params)
                })
            },

            // Método que trae todos los datos sobre el consolidado a consultar
            dispatcher(params) {

                switch (params.type_response) {
                    case 'pdf':
                        this.executePDF(params)
                        break;
                    case 'excel':
                        toastr.success('En desarrollo...')
                        break;
                    default:
                        this.executeDefault(params)
                }
            },
            executePDF(params) {
                var esc = encodeURIComponent;
                var query = Object.keys(params)
                    .map(k => esc(k) + '=' + esc(params[k]))
                    .join('&');
                let url = params.url_consolidated + '?' + query;
                window.open(url);
            },
            executeDefault(params) {
                axios.get(params.url_consolidated, {params}).then(res => {
                    // Cuando la variable local tiene la información, le asignamos valor true a la variable
                    // state, para que renderice el componente table-consolidated
                    this.mainComponent.percentages = res.data
                    this.state = true
                })
            },
        },
        destroyed() {
            this.$bus.$off('SelectedCurrentView@MenuStatistics')
        }
    }
</script>

<style scoped>

</style>