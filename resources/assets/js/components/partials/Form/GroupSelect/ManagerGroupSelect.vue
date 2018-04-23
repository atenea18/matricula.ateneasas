<template>
    <div class="row">
        <div class="col-md-4">
            <select-grade :objectInput="objectToSelectGrade"></select-grade>
        </div>
        <div class="col-md-4">
            <!-- Depende de grade -->
            <select-group :objectInput="objectToSelectGroup"></select-group>
        </div>
        <div class="col-md-4">
            <!-- Depende de group-->
            <select-period :objectInput="objectToSelectPeriod"></select-period>
        </div>

    </div>
</template>

<script>
    import SelectGrade from "../SelectGrade";
    import SelectGroup from "../SelectGroup";
    import SelectPeriod from "../SelectPeriod";

    export default {
        components: {
            SelectPeriod,
            SelectGrade, SelectGroup
        },
        props: {
            /*
                referenceId:"",
                referenceToReciveObjectSelected: 'to-receive-object-selected@' + this.referenceId + '.identificador',
             */
            objectInput: {type: Object}
        },
        name: "manager-group-select",
        data() {
            return {
                objectToManagerGroupSelect:{
                    grade_id:0,
                    group_id:0,
                    periods_id:0,
                },
                objectToSelectGrade: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@' + this.objectInput.referenceId + '.grades',
                    referenceGetObjectSelected: 'get-object-grade-selected@' + this.objectInput.referenceId + '.grades'
                },
                objectToSelectGroup: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@' + this.objectInput.referenceId + '.group',
                    referenceGetObjectSelected: 'get-object-selected@' + this.objectInput.referenceId + '.group',
                    referenceToReciveObjectSelected: 'to-receive-object-selected@' + this.objectInput.referenceId + '.group',
                },
                objectToSelectPeriod: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@' + this.objectInput.referenceId + '.periods',
                    referenceGetObjectSelected: 'get-object-selected@' + this.objectInput.referenceId + '.periods',
                    referenceToReciveObjectSelected: 'to-receive-object-selected@' + this.objectInput.referenceId + '.periods',
                }
            }
        },
        created() {
            this.managerEvents()
        },
        methods: {
            managerEvents() {

                this.$bus.$on(this.objectToSelectGrade.referenceGetObjectSelected, object => {
                    this.objectToManagerGroupSelect.grade_id = object.id
                    this.$bus.$emit(this.objectToSelectGroup.referenceToReciveObjectSelected, object);
                })

                this.$bus.$on(this.objectToSelectGroup.referenceGetObjectSelected, object => {
                    this.objectToManagerGroupSelect.group_id = object.id
                    this.$bus.$emit(this.objectToSelectPeriod.referenceToReciveObjectSelected, object);
                    if(this.objectToManagerGroupSelect.periods_id != 0){
                        this.$bus.$emit(this.objectInput.referenceToReciveObjectSelected, this.objectToManagerGroupSelect);
                    }

                    //console.log("seleccionó un grupo: " + object.name)
                })
                this.$bus.$on(this.objectToSelectPeriod.referenceGetObjectSelected, objectId => {
                    this.objectToManagerGroupSelect.periods_id = objectId
                    this.$bus.$emit(this.objectInput.referenceToReciveObjectSelected, this.objectToManagerGroupSelect);
                })
            },
        },

        destroyed() {
            this.$bus.$off(this.objectToSelectGrade.referenceGetObjectSelected);
            this.$bus.$off(this.objectToSelectGroup.referenceGetObjectSelected);
            this.$bus.$off(this.objectToSelectPeriod.referenceGetObjectSelected);
        }

    }
</script>

<style scoped>

</style>