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
                        <li :class="currentView=='stats-consolidated'?'active':''">
                            <a href="#consolidated" @click="setCurrentView('stats-consolidated')">Consolidados
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li :class="currentView=='stats-rating'?'active':''">
                            <a href="#rating" @click="setCurrentView('stats-rating')">Puestos
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
                        <input type="checkbox" @click="filterSearch('periodo-acumulado')" v-model="objectFilterSearch.isAcumulatedPeriod"> Periodos Acumulados
                    </label>
                </div>
            </div>
        </div>

        <!--
        <div class="row">
            <div class="col-md-3">
                <div class="checkbox" v-show="currentView">
                    <label>
                        <input type="checkbox" @click="getIsGroup" v-model="objectToManagerGroupSelect.isSubGroup"> Subgrupo
                    </label>
                </div>
            </div>
        </div>
        -->
        <manager-group-select v-show="currentView" :objectInput="objectToManagerGroupSelect"></manager-group-select>
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
                objectToManagerGroupSelect: {
                    referenceId: "statistics",
                    referenceToReciveObjectSelected: 'to-receive-object-selected@' + this.referenceId + '.managerGroupSelect',
                    isSubGroup: false
                },
                objectFilterSearch:{
                    isAcumulatedPeriod: false,
                },
                objectMenuStatistics:{
                    isAcumulatedPeriod: false,
                    typeViewSection: '',
                    dataManagerGroupSelect: null,
                }
            }
        },
        created(){
            this.managerEvents()


        },
        mounted(){

        },
        computed: {
            ...mapState([
                'currentView',
            ]),

        },
        methods: {
            setDataForFilter(){

                this.objectMenuStatistics.isAcumulatedPeriod = !this.objectFilterSearch.isAcumulatedPeriod
                this.objectMenuStatistics.typeViewSection = this.$store.state.currentView
            },

            filterSearch(param){
                this.setDataForFilter()
                if(!this.objectFilterSearch.isAcumulatedPeriod){

                }
                this.$bus.$emit("get-data-filter-when-acumulated-period-is-check", null)
            },
            managerEvents() {
                //Se subscribe al evento de manager-group-select, cuándo todos los select son llenados
                this.$bus.$on(this.objectToManagerGroupSelect.referenceToReciveObjectSelected, object => {
                    this.setDataForFilter()
                    this.objectMenuStatistics.dataManagerGroupSelect = object

                    //Emite evento, y pasa un objeto con los valores de los select de manager-group-select
                    // y el tipo de sección donde se encuentra, si es consolidado, puesto por grupo, etc

                    this.$bus.$emit("get-data-manager-group-select",this.objectMenuStatistics)


                    this.$bus.$on('get-data-filter-when-acumulated-period-is-check', object =>{
                        console.log(this.objectMenuStatistics)
                    })
                })


            },

            /*
            getIsGroup() {
                this.objectToManagerGroupSelect.isSubGroup = !this.objectToManagerGroupSelect.isSubGroup
                this.$bus.$emit("get-is-sub-group",this.objectToManagerGroupSelect)
            },
            */

            // Método que consite en asignar el nombre de identificación de la sección que ha sido seleccionada
            setCurrentView(view) {
                this.$store.state.currentView = view
                // Se emite un nuevo evento con la misma finalidad de pasar los valores seleccionados en
                // manager-group-select, pero este se ejecuta cuando el usuario cambia de sección.
                this.$bus.$emit("get-data-manager-group-select-change-section",view)
            },

        },
    }
</script>

<style>

</style>