<template>
    <div class="form-group padding-at">
        <label>{{title}}</label>
        <!-- Emit Event: -->
        <form-select-rename :objectInput="objectToSelectPeriods"></form-select-rename>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import FormSelectRename from "./Generic/Form/FormSelectRename";

    export default {
        components: {FormSelectRename},
        name: "select-period",
        props: {
            /*
                referenceChangeFormSelect: 'get-event-change-of-form-select@"identificador"',
                referenceGetObjectSelected 'get-object-grade-selected-@"identificador"'
                referenceEventToListing: ''
             */
            objectInput: {type: Object}
        },

        data() {
            return {
                title: "Seleccionar Periodo",
                objectToSelectPeriods: {
                    name: "periods_name",
                    arrayData: [ ],
                    referenceChangeFormSelect: this.objectInput.referenceChangeFormSelect,
                },
            }
        },

        created() {
            this.managerEvents()
        },

        computed: {
            ...mapState([
                'stateInformation',
            ]),
        },

        methods: {
            managerEvents() {
                let referenceOnGetObjectSelected = this.objectInput.referenceChangeFormSelect
                let referenceEmitObjectSelected = this.objectInput.referenceGetObjectSelected
                let referenceToReciveObjectSelected = this.objectInput.referenceToReciveObjectSelected

                //Escucha el evento de change del componente form-select
                this.$bus.$on(referenceOnGetObjectSelected, objectId => {
                    //Dispara el acción después del evento change con el objeto seleccionado
                    this.$bus.$emit(referenceEmitObjectSelected, objectId);

                })

                this.$bus.$on(referenceToReciveObjectSelected, object => {
                    this.getPeriodsByGroup(object)
                })

            },

            getPeriodsByGroup(object) {

                let url = '/ajax/getPeriodsByWorkingDay/' + object.working_day_id

                axios.get(url).then(res => {
                    this.objectToSelectPeriods.arrayData = res.data;
                })
            }
        }
    }
</script>
