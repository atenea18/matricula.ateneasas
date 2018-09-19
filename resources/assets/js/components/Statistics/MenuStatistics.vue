<template>
    <div>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                    <ul class="nav navbar-nav">
                        <li :class="currentView=='main-consolidated'?'active':''">
                            <a href="#consolidated" @click="setCurrentView('main-consolidated')">CONSOLIDADOS
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li :class="currentView=='main-percentage'?'active':''">
                            <a href="#rating" @click="setCurrentView('main-percentage')">PORCENTUALES
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li :class="currentView=='main-reprobated'?'active':''">
                            <a href="#rating" @click="setCurrentView('main-reprobated')">REPROBADOS
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li :class="currentView=='stats-rating'?'active':''">
                            <a href="#rating" @click="setCurrentView('stats-rating')">PUESTOS
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    </ul>

                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <!-- selects, check, buttons-->
        <div class="row" v-show="currentView">
            <!-- Filtros: Acumulado, Por Áreas -->
            <div class="col-md-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               v-model="SearchFilterObject.isAcumulatedPeriod"
                               @change="setEventProperties('accumulatedPeriod','check')"/>
                        Periodos Acumulados
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               v-model="SearchFilterObject.isAreas"
                               @change="setEventProperties('areas','check')"/>
                        Por Áreas
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               v-model="SearchFilterObject.isReprobated"
                               @change="setEventProperties('reprobated','check')"/>
                        Ver Reprobados
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- Selects: Grado, Grupo, Periodo -->
            <manager-group-select :objectInput="componentManagerGroupSelect"></manager-group-select>

            <!-- Buttons: PDF, Excel -->
            <div class="col-md-3">
                <!-- PDF -->
                <a href="#" class="btn btn-primary btn-sm"
                   @click="setEventProperties('pdf','click')">
                    <i class="far fa-file-pdf fa-lg fa-2x"></i>
                    PDF
                </a>
                <!-- Excel -->
                <a href="#" class="btn btn-success btn-sm"
                   @click="setEventProperties('excel','click')">
                    <i class="far fa-file-excel fa-lg fa-2x"></i>
                    EXCEL
                </a>
                <!-- ¿Todos los grupos? -->
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               v-model="SearchFilterObject.isAllGroups"
                               @change="setEventProperties('all-groups','check')"/>
                        ¿Todos los grupos?
                    </label>
                </div>
            </div>
        </div>
        <!-- //selects, check, buttons-->
    </div>
</template>

<script>

    import {mapState} from 'vuex'
    import ManagerGroupSelect from "../partials/Form/GroupSelect/ManagerGroupSelect";

    export default {
        name: "menu-statistics",
        components: {ManagerGroupSelect},
        data() {
            return {
                componentManagerGroupSelect: {
                    isSubGroup: false,
                    referenceId: "statistics",
                    referenceToReciveObjectSelected: 'to-receive-object-selected@statistics.managerGroupSelect',
                },
                SearchFilterObject: {
                    isAreas: false,
                    isAllGroups: false,
                    isAcumulatedPeriod: false,
                    isReprobated: false,
                },
                mainComponentObject: {
                    filter: {
                        isAreas: false,
                        isAcumulatedPeriod: false,
                    },
                    typeViewSection: '',
                    objectValuesManagerGroupSelect: {
                        type: "",
                        grade_id: 0,
                        group_id: 0,
                        periods_id: 1,
                        isSubGroup: false,
                    },
                },
            }
        },

        created() {
            this.managerEvents()
        },

        mounted() {

        },

        computed: {
            ...mapState([
                'currentView',
            ]),

        },
        methods: {

            initObjectComponentMain(eventProperties) {

                let filter = {
                    isAreas: this.SearchFilterObject.isAreas,
                    isAllGroups: this.SearchFilterObject.isAllGroups,
                    isAcumulatedPeriod: this.SearchFilterObject.isAcumulatedPeriod,
                    isReprobated: this.SearchFilterObject.isReprobated
                }

                this.mainComponentObject.filter = filter
                this.mainComponentObject.eventInformation = eventProperties
                this.mainComponentObject.typeViewSection = this.$store.state.currentView
            },

            setEventProperties(whoElement, typeEvent) {
                let eventProperties = {
                    type: typeEvent,
                    whoTriggered: whoElement,
                    name: "SearchFilterEvent@MenuStatistics",
                }

                this.initObjectComponentMain(eventProperties)
                this.$bus.$emit("SearchFilterEvent@MenuStatistics", null)
            },

            managerEvents() {

                //Se subscribe al evento de manager-group-select, cuándo todos los select son llenados
                this.$bus.$on(this.componentManagerGroupSelect.referenceToReciveObjectSelected, objectManagerGroup => {

                    let eventProperties = {
                        type: "selected",
                        name: "SelectedFieldsEvent@MenuStatistics",
                        whoTriggered: "componentManagerGroupSelect",
                    }

                    if (objectManagerGroup.whoTriggered == "period") {
                        eventProperties.whoTriggered = "PeriodComponentManagerGroupSelect"
                    }

                    this.initObjectComponentMain(eventProperties)
                    this.mainComponentObject.objectValuesManagerGroupSelect = objectManagerGroup

                    //Emite evento, y pasa un objeto con los valores de los select de manager-group-select
                    // y el tipo de sección donde se encuentra, si es consolidado, puesto por grupo, etc

                    //console.log('->MenuStatistics... On ChangeManagerGroupSelect@ManagerGroupSelect and Emit SelectedFieldsEvent@MenuStatistics')
                    //** Emitir Evento **//
                    this.$bus.$emit("SelectedFieldsEvent@MenuStatistics", this.mainComponentObject)


                    //** Subscribir a un Evento **//
                    this.$bus.$off('SearchFilterEvent@MenuStatistics')
                    this.$bus.$on('SearchFilterEvent@MenuStatistics', object => {
                        //console.log('->MenuStatistics... On SearchFilterEvent@MenuStatistics and Emit SelectedFieldsEvent@MenuStatistics')
                        //** Emitir Evento **//
                        this.$bus.$emit("SelectedFieldsEvent@MenuStatistics", this.mainComponentObject)
                    })
                })
            },

            // Método que consite en asignar el nombre de identificación de la sección que ha sido seleccionada
            setCurrentView(view) {
                this.$store.state.currentView = view
                // Se emite un nuevo evento con la misma finalidad de pasar los valores seleccionados en
                // manager-group-select, pero este se ejecuta cuando el usuario cambia de sección.
                let eventProperties = {
                    type: "selected",
                    name: "SelectedFieldsEvent@MenuStatistics",
                    whoTriggered: "componentManagerGroupSelect",
                }
                this.mainComponentObject.typeViewSection = view

                this.initObjectComponentMain(eventProperties)
                setTimeout(()=>{
                    this.$bus.$emit("SelectedCurrentView@MenuStatistics", this.mainComponentObject)
                },500)

            },

        },
    }
</script>

<style>

</style>