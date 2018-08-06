<template>
    <div class="form-group" style="padding-top:22px;">

        <!-- Button Configurar Desempños-->
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
            Configurar Desempeños
        </button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            Configuración de Desempeños
                        </h4>
                    </div>
                    <div class="modal-body">
                        <body-modal-performances/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    import BodyModalPerformances from './BodyModalPerformances'

    export default {
        name: "performances-manager",
        components: {
            BodyModalPerformances
        },
        props: {

        },
        data() {
            return {
                isActive: false,
                grade_id: 0,
                asignatures: [],
                areas: [],

                /* Se almacenará los datos del props objectPerformances */
                inObjectPerformances: {
                    grade_id: 0,
                    asignature_id: 0,
                    period_id: 0
                },

            }
        },
        created() {

        },
        computed: {
            ...mapState([
                'grade',
                'grades',
                'asignature',
                'periodSelected',
                'institutionOfTeacher'
            ])
        }
        ,
        mounted() {


        },

        updated() {

        },
        methods: {

        }
    }
</script>

<style>


</style>