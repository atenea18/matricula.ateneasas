<template>
    <div class="row">
        <div class="col-md-12">
            <menu-statistics></menu-statistics>
        </div>
        <div class="col-md-12">
            <keep-alive>
                <component :is="currentView" transition="fade" transition-mode="out-in"></component>
            </keep-alive>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import MenuStatistics from "./Statistics/MenuStatistics";
    import MainConsolidated from './Statistics/Consolidated/MainConsolidated'
    import MainPercentage from  './Statistics/Percentage/MainPercentage'
    import StatsRating from './Statistics/Rating/StatsRating'
    import StatsTeachers from './Statistics/Teachers/StatsTeachers'
    import { VueGoodTable } from 'vue-good-table';

    export default {
        name: "statistics-manager",
        components: {
            MenuStatistics,
            MainConsolidated,
            MainPercentage,
            StatsRating,
            StatsTeachers,
            VueGoodTable,

        },
        data() {
            return {
                objectToStatistics: {
                    selectedPeriodId: 0,
                    state: false,
                },
            }
        },
        created() {
            this.getGrades()
            this.stateScaleValoration()

        },

        updated() {
            //this.getConexion()
        },
        computed: {
            ...mapState([
                'currentView',
                'stateScale',
            ]),

        },
        methods: {
            getGrades() {
                this.$store.dispatch('grades')
            },
            stateScaleValoration() {
                this.$store.dispatch('scaleValoration')
            },

        }
    }
</script>

<style>
    .fade-transition {
        transition: opacity 0.2s ease;
    }

    .fade-enter, .fade-leave {
        opacity: 0;
    }

    .form-control {
        border-radius: 0px;
        display: block;
        width: 100%;
        padding: 6px 10px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }
</style>