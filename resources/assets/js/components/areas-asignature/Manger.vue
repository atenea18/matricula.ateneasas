<template>
    <div class="row">
        <div class="col-md-12">
            <div>
                <tabs>
                    <tab name="Asignación">
                        <div class="row">
                            <div class="col-md-12">
                                <!--
                                {{grades}}
                                {{stringTest}}
                                {{counter}}
                                {{getDouble}}
                                <div>
                                    <button @click="increment">+</button>
                                    <button @click="decrement">-</button>
                                    <button @click="increments10">+10</button>
                                    <button @click="incrementAsync">+Async</button>
                                </div>
                                -->
                                <add-group></add-group>
                            </div>
                        </div>
                    </tab>
                    <tab name="Ver Grupos">
                        <div class="row">
                            <div class="col-md-12">
                                <at-table-pensum></at-table-pensum>
                            </div>
                        </div>
                    </tab>
                    <tab name="Pensum Por Grado">
                        <div class="row">
                            <div class="col-md-12">
                                <add></add>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <!--<accordion-areas></accordion-areas>-->
                            </div>
                        </div>
                    </tab>
                    <tab name="Ver Grados">
                        <div class="row">
                            <div class="col-md-12">
                                <table-custom></table-custom>
                            </div>
                        </div>
                    </tab>

                </tabs>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapMutations, mapGetters } from 'vuex'
    import Add from './Add';
    import AddGroup from './AddGroup.vue';
    import TableCustom from "./Table";
    import AtTablePensum from '../partials/TableData/AtTablePensum'

    export default {
        name: "manger",
        components: {
            TableCustom,
            Add, AddGroup, AtTablePensum
        },
        created(){
            this.getGrades()
            this.getAreas()
            this.getAsignatures()
            this.getSubjectsType()
            this.getTeachers()
        },
        computed: {
            //...mapState(['grades','areas']),
            ...mapGetters(['getDouble']),

            /*
            double(){
                return this.$store.getters.getDouble
            },
            */

            stringTest() {
                return "Si"
            }
        },
        data() {
            return {
                title: 'Adicionar Áreas',
            }
        },
        methods: {
            ...mapMutations(['increment','decrement']),

            increments10() {
                this.$store.commit('increment',{
                    number:10
                })
            },

            incrementAsync(){
                this.$store.dispatch('incrementAsync', { number:5 })
                    .then( () => {
                        console.log('Actions finished')
                    })
            },
            getGrades(){
                this.$store.dispatch('grades')
            },
            getAreas(){
                this.$store.dispatch('areas')
            },
            getAsignatures(){
                this.$store.dispatch('asignatures')
            },
            getSubjectsType(){
                this.$store.dispatch('subjectsType')
            },
            getTeachers(){
                this.$store.dispatch('teachers')
            }

        }

    }
</script>

<style scoped>


</style>