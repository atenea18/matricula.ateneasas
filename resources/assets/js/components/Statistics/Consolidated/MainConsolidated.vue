<template>
    <div>
        <div class="row">
            <div class="col-md-3">
                <form class="navbar-form navbar-left">
                    <a v-if="data.periods_id" type="submit" class="btn btn-default" target="_blank"
                       :href="urlPdf">PDF</a>
                </form>
            </div>

            <!--<button @click="imprint"> pdf</button>-->
        </div>
        <div class="row">
            <div class="col-md-12">
                <template v-if="state">
                    <br>
                    <table-consolidated id="table-consolidated" :objectInput="objectToStatsConsolidated"></table-consolidated>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import ManagerGroupSelect from "../../partials/Form/GroupSelect/ManagerGroupSelect";
    import TableConsolidated from "./Table/TableConsolidated";


    export default {
        components: {
            TableConsolidated,
            ManagerGroupSelect
        },
        name: "main-consolidated",
        data() {
            return {
                objectToManagerGroupSelect: {
                    referenceId: "statistics",
                    referenceToReciveObjectSelected: 'to-receive-object-selected@' + this.referenceId + '.managerGroupSelect',
                    isSubGroup: false
                },
                objectToStatsConsolidated: {
                    asignatures: [],
                    enrollments: [],
                    params: {}
                },

                state: false,
                data: {},
                urlPdf: "",
                urlStream :''


            }
        },
        created() {
            this.managerEvents()
        },
        computed: {
            ...mapState([
                'institutionOfTeacher',
                'periodsworkingday'
            ]),

        },
        methods: {
            imprint() {
                /*
                let url = '/ajax/PDFF'
                let table = document.getElementById('table-consolidated')
                let data = {
                    contenido: ""+table.innerHTML
                }
                let _this = this
                axios.post(url, {data}, {responseType: 'arraybuffer'}).then(function (response) {
                    if (response.status == 200) {


                        const url = window.URL.createObjectURL(new Blob([response.data], {type: 'application/pdf'}));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'file.pdf');
                        document.body.appendChild(link);
                        link.click();




                    }
                }).catch(function (error) {
                    console.log(error);
                });
                */
            },
            printConsolidated() {
                if (this.data.periods_id) {

                    let url = '/pdf/consolidateByGroup'

                    let params = {
                        grade_id: this.data.grade_id,
                        group_id: this.data.group_id,
                        period_id: this.data.periods_id,
                        institution_id: this.$store.state.institutionOfTeacher.id
                    }

                    let _this = this
                    axios.get(url, {params}).then(res => {
                        var blob = new Blob([this.res], {type: 'application/pdf'});
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "report.pdf";
                        link.click();
                    })

                }
            },

            managerEvents() {
                //Se subscribe al evento generado por menu-statistics, este le permite saber si se debe
                //mostrar la sección de consolidado con su respectiva consulta, ya que este evento devuelve
                //un objeto con los datos seleccionados de manager-group-select
                this.$bus.$off('SelectedFieldsEvent@MenuStatistics')
                this.$bus.$on('SelectedFieldsEvent@MenuStatistics', componentObjectMenuStatistics => {




                    //Se asigna el objeto fieldSelects a variable local, objeto que tiene los datos seleccionados
                    //de manager-group-select
                    this.objectToStatsConsolidated.params = componentObjectMenuStatistics

                    this.managerSelectedFieldsEvent(componentObjectMenuStatistics)

                })

                // Se subscribe al evento generado por menu-statistics, para generar una nueva consulta de consolidados
                // si algún select de manager-group-select fue modificado
                this.$bus.$off('SelectedCurrentVIew@MenuStatistics')
                this.$bus.$on('SelectedCurrentVIew@MenuStatistics', object => {
                    this.managerSelectedFieldsEvent()
                })
            },

            managerSelectedFieldsEvent(componentObject) {
                //console.log("->StatsConsolidated... Function managerSelectedFieldsEvent")

                if (typeof componentObject == "undefined") {
                    componentObject = this.objectToStatsConsolidated.params
                }

                if (this.$store.state.currentView == 'main-consolidated') {
                    if (componentObject.eventInformation.whoTriggered == "areas" || componentObject.eventInformation.whoTriggered == "componentManagerGroupSelect") {
                        //console.log("->StatsConsolidated... Evento realiza consulta de consolidados")
                        this.managerQueryForFilterConsolidated(componentObject)
                    }
                }

            },

            managerQueryForFilterConsolidated(componentObject) {

                let params = {

                    institution_id: this.$store.state.institutionOfTeacher.id,
                    grade_id: componentObject.objectValuesManagerGroupSelect.grade_id,
                    group_id: componentObject.objectValuesManagerGroupSelect.group_id,
                    periods_id: componentObject.objectValuesManagerGroupSelect.periods_id,

                    is_filter_areas: componentObject.filter.isAreas,
                    is_subgroup: componentObject.objectValuesManagerGroupSelect.isSubGroup,

                    url_subjects: '',
                    url_consolidated: '',
                    type_response:'json',

                }

                params.url_subjects = '/ajax/getSubjects'
                params.url_consolidated = '/ajax/getTableConsolidated'

                this.getContentConsolidated(params)
            },

            getContentConsolidated(params) {
                //this.state = false
                axios.get(params.url_subjects, {params}).then(res => {
                    //Trae las asignaturas correpondiente a los datos seleccionados
                    this.objectToStatsConsolidated.asignatures = res.data
                    //console.log(this.objectToStatsConsolidated.asignatures)
                    //Trae la información correspondiente al consolidado
                    this.getTableConsolidated(params)
                })
            },

            // Método que trae todos los datos sobre el consolidado a consultar
            getTableConsolidated(params) {

                this.urlPdf = "/pdf/consolidateByGroup?grade_id=" + params.grade_id +
                    "&group_id=" + params.group_id +
                    "&period_id=" + params.periods_id + "&institution_id=" + params.institution_id +
                    "&is_subgroup=" + params.isSubGroup


                this.data = params
                axios.get(params.url_consolidated, {params}).then(res => {
                    // Asignamos objeto que contiene la información correspondiente del consolidado
                    // a variable local, variable que es pasada como parametro al componente table-consolidated
                    this.objectToStatsConsolidated.enrollments = res.data
                    // Cuando la variable local tiene la información, le asignamos valor true a la variable
                    // state, para que renderice el componente table-consolidated
                    this.state = true
                    this.$bus.$emit("ya", params)
                })
            },
        }

    }
</script>

<style scoped>

</style>