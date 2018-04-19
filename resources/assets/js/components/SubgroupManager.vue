<template>
    <div class="row">
        <!--
        <div class="col-md-12">
            <div>
                <tabs>
                    <tab name="Ver">
                        <div class="row">
                            <div class="col-md-12">

                            </div>
                        </div>
                    </tab>
                    <tab name="Asignación">
                        <div class="row">
                            <div class="col-md-12">
                                <add-subgroup></add-subgroup>
                            </div>
                        </div>
                    </tab>
                </tabs>
            </div>
        </div>
        -->
    </div>

</template>

<script>
    import {mapState} from 'vuex'
    import AddSubgroup from './academic-assignment/subgroup/AddSubgroup'

    export default {
        name: "subgroup-manager",
        components: {
            AddSubgroup
        },
        created() {
            this.getGrades()
            this.getAreas()
            this.getAsignatures()
            this.getSubjectsType()
            this.getTeachers()
        },
        mounted(){

        },
        computed:{
            ...mapState(['grades']),
        },
        methods: {
            getGrades() {
                this.$store.dispatch('grades')

            },
            getAreas() {
                this.$store.dispatch('areas')
            },
            getAsignatures() {
                this.$store.dispatch('asignatures')
            },
            getSubjectsType() {
                this.$store.dispatch('subjectsType')
            },
            getTeachers() {
                this.$store.dispatch('teachers')
            }
        }
    }
</script>

<style scoped>

</style>