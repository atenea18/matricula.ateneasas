<template>
    <div class="row">
        <div class="col-md-12">
            <div>
                <tabs>
                    <tab name="Pensum">
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
                    <tab name="Ver">
                        <div class="row">
                            <div class="col-md-12">
                                <table-custom></table-custom>
                            </div>
                        </div>
                    </tab>
                    <tab name="Asignación">
                        <div class="row">
                            <div class="col-md-12">
                                {{stringTest}}
                                {{counter}}
                                {{getDouble}}
                                <div>
                                    <button @click="increment">+</button>
                                    <button @click="decrement">-</button>
                                    <button @click="increments10">+10</button>
                                    <button ></button>
                                </div>
                                <!--<add-group></add-group>-->
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

    export default {
        name: "manger",
        components: {
            TableCustom,
            Add, AddGroup
        },
        computed: {
            ...mapState(['counter']),
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
        }
    }
</script>

<style scoped>

</style>