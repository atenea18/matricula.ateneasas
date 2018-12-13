<template>
    <div class="row">
        <div class="col-md-12">
            <selects-evaluation :props-data="propsData"/>
        </div>

        <div class="col-md-12">
            <br>
            <manager-performances/>
        </div>
        <div class="col-md-12">
            <template v-if="state_table_evaluation">
                <relation-pensum-performances/>
            </template>
            <br>
        </div>
        <div class="col-md-12">
            <template v-if="state_table_evaluation">
                <table-evaluation/>
            </template>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import SelectsEvaluation from './Evaluation/GroupSelect/SelectsEvaluation';
    import ManagerPerformances from './Evaluation/Performances/ManagerPerformances';
    import TableEvaluation from "./Evaluation/Table/TableEvaluation";
    import RelationPensumPerformances from "./Evaluation/Table/Relation/RelationPensumPerformances"

    export default {

        name: "evaluation-x-manager",
        props: ['props-data'],
        components: {
            TableEvaluation,
            SelectsEvaluation,
            ManagerPerformances,
            RelationPensumPerformances,
        },
        data() {
            return {
                state_table_evaluation: false
            }
        },
        computed: {
            ...mapState([
                'configInstitution',
                'stateEvaluation',
                'stateScale',
                'stateInformation'
            ]),
        },
        created() {
            this.stateConfigInstitution();
            this.stateGrades();

            this.$bus.$on('eventElementsSelected@SelectsEvaluation', data => {

                this.$store.state.stateInformation.date_current = new Date()

                this.$store.state.stateEvaluation.grade_selected.id = data.grade_selected.id
                this.$store.state.stateEvaluation.group_selected.id = data.group_selected.id

                this.$store.state.stateEvaluation.period_selected.id = data.period_selected.id
                this.$store.state.stateEvaluation.period_selected.info = data.period_selected.info

                this.$store.state.stateEvaluation.area_selected.id = data.area_selected.id
                this.$store.state.stateEvaluation.asignature_selected.id = data.asignature_selected.id
                this.$store.state.stateEvaluation.asignature_selected.info = data.asignature_selected.info
                this.$store.state.stateEvaluation.rol = this.propsData.teacher_selected == null ? 'admin' : 'user'
                this.stateScaleValoration()
                this.stateParameters(data);
                //Variable global para permitir modificar las notas
                this.setStateEvaluationDisable()

            })

        },
        mounted() {

        },
        watch: {},
        methods: {
            stateConfigInstitution() {
                this.$store.dispatch('configInstitution', {group_type: true})
            },
            stateGrades() {
                this.$store.dispatch('grades')
            },
            stateParameters(data) {
                let asignature = data.asignature_selected.info

                axios.get('/ajax/evaluation-parameter').then(res => {
                    let parameters = res.data;
                    let group_type = 'group'

                    if (asignature.subjects_type_id == 3 || asignature.grade_id < 5 ) {
                        group_type = 'basic'
                    }
                    if (asignature.subjects_type_id == 2) {
                        group_type = 'subgroup'
                    }

                    if (parameters.length > 0) {
                        parameters = parameters.filter(element => {
                            return element.group_type == group_type
                        })
                    }

                    this.$store.state.stateEvaluation.parameters_selected = parameters
                    if(parameters.length > 0)
                        this.$store.state.stateEvaluation.parameter_selected_id = parameters[0].id

                    this.getCollectionNotes(data)
                    this.$bus.$emit('eventReadyDataState@EvaluationXManager', null)
                });

            },
            stateScaleValoration() {
                this.$store.dispatch('scaleValoration')
            },
            getCollectionNotes(data) {
                this.state_table_evaluation = false
                let params = {
                    group_id: data.group_selected.id,
                    asignature_id: data.asignature_selected.id,
                    period_id: data.period_selected.id,
                }

                axios.get('/ajax/evaluation-collections-notes', {params}).then(res => {
                    if (typeof res.data == 'object') {
                        this.state_table_evaluation = true
                        this.$store.state.stateEvaluation.collectionNotes = res.data
                        //console.log(res.data)
                    }
                })
            },
            setStateEvaluationDisable() {
                let date = this.$store.state.stateInformation.date_current.getTime()
                let start_date = new Date(this.$store.state.stateEvaluation.period_selected.info.start_date).getTime()
                let end_date = new Date(this.$store.state.stateEvaluation.period_selected.info.end_date).getTime()

                if (this.$store.state.stateEvaluation.rol == 'admin' || (start_date < date && date < end_date)) {
                    this.$store.state.stateEvaluation.disabled = false
                } else {
                    this.$store.state.stateEvaluation.disabled = true
                }
            },
            initToast() {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            },

        }

    }
</script>

<style>
    .modal-content {
        border-radius: 0px;
    }

    .modal-dialog {
        width: 88%;
        margin: 30px auto;
    }

</style>