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
                <div class="checkbox">
                    <label>
                        <input type="checkbox" @click="getIsGroup" v-model="objectToManagerGroupSelect.isSubGroup"> Subgrupo
                    </label>
                </div>
            </div>
        </div>

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
            }
        },
        created(){
            this.managerEvents()
        },
        computed: {
            ...mapState([
                'currentView',
            ]),

        },
        methods: {
            managerEvents() {
                this.$bus.$on(this.objectToManagerGroupSelect.referenceToReciveObjectSelected, object => {
                    let objectToStats = {
                        fieldSelects: object,
                        type: this.$store.state.currentView
                    }
                    this.$bus.$emit("spire",objectToStats)
                })
            },
            getIsGroup() {
                this.objectToManagerGroupSelect.isSubGroup = !this.objectToManagerGroupSelect.isSubGroup
                this.$bus.$emit("get-is-sub-group",this.objectToManagerGroupSelect)
            },
            setCurrentView(view) {
                this.$store.state.currentView = view
                this.$bus.$emit("get-spire",view)
            },

        },
    }
</script>

<style>

</style>