<template>
    <div>
        <div class="row">
            <div class="col-md-3">
                <form class="navbar-form navbar-left">
                    <a v-if="data.periods_id" type="submit" class="btn btn-default" target="_blank"
                       :href="urlPdf">PDF</a>
                </form>
            </div>
        </div>
        <!--<manager-group-select :objectInput="objectToManagerGroupSelect"></manager-group-select>-->
        <div class="row">
            <div class="col-md-12">
                <template v-if="state">
                    <table-consolidated :objectInput="objectToStatsConsolidated"></table-consolidated>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import ManagerGroupSelect from "../../partials/Form/GroupSelect/ManagerGroupSelect";
    import TableConsolidated from "./TableConsolidated";


    export default {
        components: {
            TableConsolidated,
            ManagerGroupSelect
        },
        name: "consolidated",
        data() {
            return {
                objectToManagerGroupSelect: {
                    referenceId: "statistics",
                    referenceToReciveObjectSelected: 'to-receive-object-selected@' + this.referenceId + '.managerGroupSelect',
                    isSubGroup: false
                },
                objectToStatsConsolidated: {
                    asignatures: [],
                    enrollments: [],
                    data:{}
                },

                state: false,
                data: {},
                urlPdf: ""
            }
        },
        created() {
            this.managerEvents()
        },
        computed: {
            ...mapState([
                'institutionOfTeacher',
            ]),

        },
        methods: {

            getIsGroup() {
                this.objectToManagerGroupSelect.isSubGroup = !this.objectToManagerGroupSelect.isSubGroup
                this.$bus.$emit("get-is-sub-group",this.objectToManagerGroupSelect)
            },
            printConsolidated() {
                //console.log(this.data)
                if (this.data.periods_id) {

                    let url = '/pdf/consolidateByGroup'

                    let params = {
                        grade_id: this.data.grade_id,
                        group_id: this.data.group_id,
                        period_id: this.data.periods_id,
                        institution_id: this.$store.state.institutionOfTeacher.id
                    }

                    let _this = this
                    axios.get(url, {params}).then(res => {
                        var blob = new Blob([this.res], {type: 'application/pdf'});
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "report.pdf";
                        link.click();
                    })

                }
            },

            managerEvents() {
                this.$bus.$on('spire', object => {
                    this.objectToStatsConsolidated.data = object.fieldSelects
                    if(object.type == 'stats-consolidated'){
                        this.getAsignaturesConsolidated(object.fieldSelects)
                    }
                })
                this.$bus.$on('get-spire', object => {
                    if(object == 'stats-consolidated'){
                        this.getAsignaturesConsolidated(this.objectToStatsConsolidated.data)
                    }
                })
            },

            getAsignaturesConsolidated(object) {
                this.state = false
                let url = '/ajax/getAsignaturesGroupPensum'

                let params = {
                    grade_id: object.grade_id,
                    group_id: object.group_id,
                    periods_id: object.periods_id,
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    isSubGroup: object.isSubGroup

                }

                axios.get(url, {params}).then(res => {
                    this.objectToStatsConsolidated.asignatures = res.data
                    this.getConsolidated(object)
                })
            },


            getConsolidated(object) {

                let params = {
                    grade_id: object.grade_id,
                    group_id: object.group_id,
                    periods_id: object.periods_id,
                    institution_id: this.$store.state.institutionOfTeacher.id,
                    isSubGroup: object.isSubGroup
                }

                this.urlPdf = "/pdf/consolidateByGroup?grade_id=" + params.grade_id +
                    "&group_id=" + params.group_id +
                    "&period_id=" + params.periods_id + "&institution_id=" + params.institution_id +
                    "&is_subgroup=" +params.isSubGroup

                this.data = params
                //console.log(this.data.isSubGroup)

                let url = '/ajax/getConsolidated'

                axios.get(url, {params}).then(res => {
                    this.objectToStatsConsolidated.enrollments = res.data
                    this.state = true
                })

            },
        }

    }
</script>

<style scoped>

</style>