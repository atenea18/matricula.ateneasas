<template>
    <div>
        <manager-group-select :objectInput="objectToManagerGroupSelect"></manager-group-select>
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
    import ManagerGroupSelect from "../../partials/Form/GroupSelect/ManagerGroupSelect";
    import TableConsolidated from "./TableConsolidated";

    export default {
        components: {
            TableConsolidated,
            ManagerGroupSelect},
        name: "consolidated",
        data() {
            return {
                objectToManagerGroupSelect: {
                    referenceId: "statistics",
                    referenceToReciveObjectSelected: 'to-receive-object-selected@' + this.referenceId + '.managerGroupSelect',
                },
                objectToStatsConsolidated: {
                    asignatures: [],
                    enrollments: []
                },
                state: false
            }
        },
        created() {
            this.managerEvents()
        },
        methods: {
            managerEvents() {
                this.$bus.$on(this.objectToManagerGroupSelect.referenceToReciveObjectSelected, object => {
                    this.getAsignaturesConsolidated(object)
                })
            },

            getAsignaturesConsolidated(object) {
                this.state = false
                let url = '/ajax/getAsignaturesGroupPensum'

                let params = {
                    grade_id: object.grade_id,
                    group_id: object.group_id,
                    periods_id: object.periods_id
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
                    periods_id: object.periods_id
                }
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