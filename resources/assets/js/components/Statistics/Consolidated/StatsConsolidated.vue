<template>
    <div>
        <div class="row">
            <div class="col-md-3">
                <form class="navbar-form navbar-left">
                    <a v-if="data.periods_id" type="submit" class="btn btn-default" target="_blank"
                       :href="urlPdf">PDF</a>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <template v-if="state">
                    <table-consolidated :objectInput="objectToStatsConsolidated"></table-consolidated>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import ManagerGroupSelect from "../../partials/Form/GroupSelect/ManagerGroupSelect";
    import TableConsolidated from "./TableConsolidated";


    export default {
        components: {
            TableConsolidated,
            ManagerGroupSelect
        },
        name: "consolidated",
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
                    data: {},
                    params:{}
                },

                state: false,
                data: {},
                urlPdf: ""
            }
        },
        created() {
            this.managerEvents()
        },
        computed: {
            ...mapState([
                'institutionOfTeacher',
            ]),

        },
        methods: {

            getIsGroup() {
                this.objectToManagerGroupSelect.isSubGroup = !this.objectToManagerGroupSelect.isSubGroup
                this.$bus.$emit("get-is-sub-group", this.objectToManagerGroupSelect)
            },
            printConsolidated() {
                //console.log(this.data)
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
                this.$bus.$off('get-data-manager-group-select')
                this.$bus.$on('get-data-manager-group-select', objectMenuStatistics => {

                    //Se asigna el objeto fieldSelects a variable local, objeto que tiene los datos seleccionados
                    //de manager-group-select
                    this.objectToStatsConsolidated.data = objectMenuStatistics.dataManagerGroupSelect
                    this.objectToStatsConsolidated.params = objectMenuStatistics

                    if (objectMenuStatistics.typeViewSection == 'stats-consolidated') {
                        //Si valor de type indica, que estamos en la sección de consolidados
                        //ejecutamos la consulta de consolidados
                        this.getAsignaturesConsolidated(this.objectToStatsConsolidated.params)
                    }

                })

                // Se subscribe al evento generado por menu-statistics, para generar una nueva consulta de consolidados
                // si algún select de manager-group-select fue modificado
                this.$bus.$off('get-data-manager-group-select-change-section')
                this.$bus.$on('get-data-manager-group-select-change-section', object => {
                    if (object == 'stats-consolidated') {
                        this.getAsignaturesConsolidated(this.objectToStatsConsolidated.params)
                    }
                })
            },

            //
            getAsignaturesConsolidated(object) {
                // Evita renderización del componente table-consolidated antes que el
                // objeto objectToStatsConsolidated que es pasado al componente, genere error, por no tener
                // valor asignado
                this.state = false
                let url = '/ajax/getAsignaturesGroupPensum'

                let params = {
                    grade_id: object.dataManagerGroupSelect.grade_id,
                    group_id: object.dataManagerGroupSelect.group_id,
                    periods_id: object.dataManagerGroupSelect.periods_id,
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    isSubGroup: object.dataManagerGroupSelect.isSubGroup

                }

                axios.get(url, {params}).then(res => {
                    //Trae las asignaturas correpondiente a los datos seleccionados
                    this.objectToStatsConsolidated.asignatures = res.data
                    //Trae la información correspondiente al consolidado
                    this.getConsolidated(object)
                })
            },

            // Método que trae todos los datos sobre el consolidado a consultar
            getConsolidated(object) {

                let params = {
                    grade_id: object.dataManagerGroupSelect.grade_id,
                    group_id: object.dataManagerGroupSelect.group_id,
                    periods_id: object.dataManagerGroupSelect.periods_id,
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    isSubGroup: object.dataManagerGroupSelect.isSubGroup,
                    isAcumulatedPeriod: object.isAcumulatedPeriod
                }

                this.urlPdf = "/pdf/consolidateByGroup?grade_id=" + params.grade_id +
                    "&group_id=" + params.group_id +
                    "&period_id=" + params.periods_id + "&institution_id=" + params.institution_id +
                    "&is_subgroup=" + params.isSubGroup

                this.data = params
                //console.log(params.isAcumulatedPeriod)

                let url = '/ajax/getConsolidated'

                axios.get(url, {params}).then(res => {
                    // Asignamos objeto que contiene la información correspondiente del consolidado
                    // a variable local, variable que es pasada como parametro al componente table-consolidated
                    this.objectToStatsConsolidated.enrollments = res.data

                    // Cuando la variable local tiene la información, le asignamos valor true a la variable
                    // state, para que renderice el componente table-consolidated
                    this.state = true
                })

            },
        }

    }
</script>

<style scoped>

</style>