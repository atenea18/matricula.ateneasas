<template>
    <div class="form-group" style="padding-top:22px;">
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal"> Configurar Desempeños
        </button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Configuración de Desempeños</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills">
                                    <li role="presentation" :class="!isActive?'at-active':''">
                                        <a href="#" @click="clickOptions">
                                            <i class="fas fa-search"></i> Consultar
                                        </a>
                                    </li>
                                    <li role="presentation" :class="isActive?'at-active':''">
                                        <a href="#" @click="clickOptions">
                                            <i class="fas fa-plus-circle"></i> Crear Nuevo
                                        </a>
                                    </li>
                                </ul>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                              <!--  <at-select-object :objectSelect="objectGrade"></at-select-object> -->
                            </div>
                            <!--
                            <div class="col-md-3">
                                <at-select :type="{name:'Areas', nameEv:'area-ev', tby:'null', validate:true}"
                                           :data="areas"></at-select>
                            </div>
                            <div class="col-md-3">
                                <at-select
                                        :type="{name:'Asignatura', nameEv:'asignature-ev', tby:'null', validate:true, id:asignature.id}"
                                        :data="asignatures"></at-select>
                            </div>
                            -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                Contenido
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import AtSelectObject from '../../partials/AtSelectObject'

    export default {
        name: "performances-manager",
        components: {
            AtSelectObject
        },
        props: {
            objectPerformances: {type: Object}
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
                'asignature',
                'grades',
                'institutionOfTeacher'
            ]),

            /* Objeto para el select grade*/
            objectGrade() {
                return {
                    nameSelect: 'Grado',
                    nameEvent: 'grade-ev',
                    nameDependencie: 'null',
                    isValidate: true,
                    idSelected: 0,
                    rows: this.$store.state.grades
                }
            }
        }
        ,
        mounted() {
            this.inObjectPerformances = this.objectPerformances
            console.log(this.$store.state.grades)
        },

        updated() {

        },
        methods: {
            clickOptions() {
                this.isActive = !this.isActive
            },
            getAsignatures() {
                let params = {
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    grade_id: this.grade_id
                }
                axios.get('/ajax/asignaturesByGrade', {params}).then(res => {
                    this.asignatures = res.data;
                    console.log(res.data)
                })
            },
            getAreas() {
                let params = {
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    grade_id: this.grade_id
                }
                axios.get('/ajax/areasByGrade', {params}).then(res => {
                    this.areas = res.data;
                })
            },
        }
    }
</script>

<style>
    .nav-pills > li > a {
        border-radius: 0px !important;
    }

    .at-active {
        background: #eee
    }

</style>