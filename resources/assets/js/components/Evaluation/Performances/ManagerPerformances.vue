<template>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <!-- Button Configurar Desempños-->
                <button v-if="state_button"  type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
                    Configurar Desempeños
                </button>
            </div>
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
                           <content-performances></content-performances>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="checkbox" v-if="state_button">
                    <label>
                        <input v-model="is_overcoming_report" type="checkbox"/>
                        Superaciones Informe Final
                    </label>
                </div>
            </div>
        </div>
        <div v-if="state_button" class="col-md-3">
            <div v-if="!stateInformation.is_conexion">
                <small style="margin-top: 10px; color: red; text-align: center;"><b>Sin internet, conectese a una red disponible</b></small>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import ContentPerformances from './ContentPerformances'

    export default {
        name: "manager-performances",
        components: {
            ContentPerformances,
        },
        data(){
            return{
                state_button: false,
                is_overcoming_report: false,
            }
        },
        watch:{
            is_overcoming_report: function () {
                this.$bus.$emit('eventShowOvercomingReport@ManagerPerformances', this.is_overcoming_report)
            }
        },
        created(){
            this.$bus.$on('eventReadyDataState@EvaluationXManager', data => {
                //Se subscribe al evento para habilitar el botón configuración de desempeños
                this.state_button = true
                this.is_overcoming_report = false
                this.stateGetConexion
            })
        },
        computed: {
            ...mapState([
                'stateInformation',
            ]),
        },
        methods:{
            stateGetConexion() {
                this.$store.dispatch('verifyConexionX')
            },
        }
    }
</script>

<style scoped>

</style>