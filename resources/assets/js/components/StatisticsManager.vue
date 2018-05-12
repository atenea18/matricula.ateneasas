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
    import StatsConsolidated from './Statistics/Consolidated/StatsConsolidated'
    import StatsRating from './Statistics/Rating/StatsRating'
    import StatsTeachers from './Statistics/Teachers/StatsTeachers'

    export default {
        name: "statistics-manager",
        components: {
            MenuStatistics,
            StatsConsolidated,
            StatsRating,
            StatsTeachers

        },
        data() {
            return {
                objectToStatistics:{
                    selectedPeriodId: 0,
                    state: false,
                }
            }
        },
        created() {
            this.getGrades()
            this.getInstitutionOfTeacher()
            /*
            this.getGradeById(this.group.grade_id)
            this.getAsignatureById(this.asignatureid, this.group.grade_id)
            this.getParameters()

            this.getGroupPensum(this.group.id, this.asignatureid, 1)
            this.getPeriodsByWorkingDay(this.group.working_day_id);
            this.getInstitutionOfTeacher()
            */
        },

        updated() {
            //this.getConexion()
        },
        computed: {
            ...mapState([
                'currentView',
                /*
                'grade',
                'asignature',
                'periodSelected',
                'institutionOfTeacher',
                'periodsworkingday',
                'isCollection',
                'isConexion',
                'groupPensum'
                */
            ]),

        },
        methods: {
            getGrades() {
                this.$store.dispatch('grades')
            },
            getInstitutionOfTeacher() {
                this.$store.dispatch('institutionOfTeacher')
            },

            /*
            getParameters() {
                this.$store.dispatch('parameters')
            },
            getAsignatureById(asignatureid, grade_id) {
                this.$store.dispatch('asignatureById', {
                    asignatureid: asignatureid,
                    grade_id: grade_id
                })
            },
            getGroupPensum(group_id, asignatures_id, school_year_id) {
                this.$store.dispatch('groupPensum', {
                    group_id: group_id,
                    asignatures_id: asignatures_id,
                    school_year_id: school_year_id
                })
            },
            getGradeById(grade_id) {
                this.$store.dispatch('gradeById', {
                    grade_id: grade_id
                })
            },
            getEvaluationsByPeriod() {
                this.$store.state.isCollection = false
                this.$store.state.periodSelected = this.periodid
                this.getCollectionNotes(this.group.id, this.asignatureid, this.$store.state.periodSelected)
            },
            getPeriodsByWorkingDay(workingdayid) {
                this.$store.dispatch('periodsByWorkingDay', {
                    workingdayid: workingdayid
                })
            },

            getCollectionNotes(groupid, asignatureid, periodid) {
                this.$store.dispatch('collectionNotes', {
                    groupid: groupid,
                    asignatureid: asignatureid,
                    periodid: periodid
                })
            },


            getConexion() {
                this.$store.dispatch('verifyConexion')
            }
            */

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