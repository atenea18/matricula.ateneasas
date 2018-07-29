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
                        <li :class="currentView=='stats-rating'?'active':''">
                            <a href="#rating" @click="setCurrentView('stats-rating')">PUESTOS
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    </ul>

                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <div class="row">
            <div class="col-md-3">
                <div class="checkbox" v-show="currentView">
                    <label>
                        <input type="checkbox" @change="filterSearch('accumulatedPeriod')"
                               v-model="SearchFilterObject.isAcumulatedPeriod"> Periodos Acumulados
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="checkbox" v-show="currentView">
                    <label>
                        <input type="checkbox" @change="filterSearch('areas')" v-model="SearchFilterObject.isAreas"> Por
                        Áreas
                    </label>
                </div>
            </div>
        </div>
        <!--
        <div class="row">
            <div class="col-md-3">
                <div class="checkbox" v-show="currentView">
                    <label>
                        <input type="checkbox" @click="getIsGroup" v-model="componentManagerGroupSelect.isSubGroup"> Subgrupo
                    </label>
                </div>
            </div>
        </div>
        -->
        <manager-group-select v-show="currentView" :objectInput="componentManagerGroupSelect"></manager-group-select>
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
                    referenceId: "statistics",
                    referenceToReciveObjectSelected: 'to-receive-object-selected@statistics.managerGroupSelect',
                    isSubGroup: false
                },
                SearchFilterObject: {
                    isAcumulatedPeriod: false,
                    isAreas: false,
                },
                mainComponentObject: {
                    filter: {
                        isAcumulatedPeriod: false,
                        isAreas: false,
                    },
                    typeViewSection: '',
                    objectValuesManagerGroupSelect:{
                        grade_id: 0,
                        group_id: 0,
                        periods_id: 1,
                        type: "",
                        isSubGroup: false
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
                    isAcumulatedPeriod: this.SearchFilterObject.isAcumulatedPeriod,
                    isAreas: this.SearchFilterObject.isAreas,
                }

                this.mainComponentObject.filter = filter
                this.mainComponentObject.eventInformation = eventProperties
                this.mainComponentObject.typeViewSection = this.$store.state.currentView
            },

            filterSearch(EventName) {
                let eventProperties = this.getEventProperties({
                    name: "SearchFilterEvent@MenuStatistics",
                    whoTriggered: EventName,
                    type: "change"
                })


                this.initObjectComponentMain(eventProperties)

                //** Emitir Evento **//
                this.$bus.$emit("SearchFilterEvent@MenuStatistics", null)
            },
            managerEvents() {

                //Se subscribe al evento de manager-group-select, cuándo todos los select son llenados
                this.$bus.$on(this.componentManagerGroupSelect.referenceToReciveObjectSelected, object => {

                    let eventProperties = this.getEventProperties({
                        name: "SelectedFieldsEvent@MenuStatistics",
                        whoTriggered: "componentManagerGroupSelect",
                        type: "selected"
                    })

                    if(object.whoTriggered == "period"){
                        eventProperties.whoTriggered = "PeriodComponentManagerGroupSelect"
                    }

                    this.initObjectComponentMain(eventProperties)
                    this.mainComponentObject.objectValuesManagerGroupSelect = object

                    //Emite evento, y pasa un objeto con los valores de los select de manager-group-select
                    // y el tipo de sección donde se encuentra, si es consolidado, puesto por grupo, etc

                    console.log('->MenuStatistics... On ChangeManagerGroupSelect@ManagerGroupSelect and Emit SelectedFieldsEvent@MenuStatistics')
                    //** Emitir Evento **//
                    this.$bus.$emit("SelectedFieldsEvent@MenuStatistics", this.mainComponentObject)



                    //** Subscribir a un Evento **//
                    this.$bus.$off('SearchFilterEvent@MenuStatistics')
                    this.$bus.$on('SearchFilterEvent@MenuStatistics', object => {

                        console.log('->MenuStatistics... On SearchFilterEvent@MenuStatistics and Emit SelectedFieldsEvent@MenuStatistics')
                        //** Emitir Evento **//
                        this.$bus.$emit("SelectedFieldsEvent@MenuStatistics", this.mainComponentObject)
                    })
                })


            },

            getEventProperties(objectProperties){
                let eventProperties = {
                    name: objectProperties.name,
                    whoTriggered: objectProperties.whoTriggered,
                    type: objectProperties.type
                }
                return eventProperties
            },

            // Método que consite en asignar el nombre de identificación de la sección que ha sido seleccionada
            setCurrentView(view) {
                this.$store.state.currentView = view
                // Se emite un nuevo evento con la misma finalidad de pasar los valores seleccionados en
                // manager-group-select, pero este se ejecuta cuando el usuario cambia de sección.
                this.$bus.$emit("SelectedCurrentVIew@MenuStatistics", view)
            },

        },
    }
</script>

<style>

</style>