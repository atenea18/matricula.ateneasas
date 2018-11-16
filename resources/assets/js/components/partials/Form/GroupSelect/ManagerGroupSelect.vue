<template>
    <div>
        <div class="col-md-4">
            <select-grade :objectInput="objectToSelectGrade"/>
        </div>
        <div class="col-md-4">
            <!-- Depende de grade -->
            <select-group v-show="!objectToManagerGroupSelect.isSubGroup"
                          :objectInput="objectToSelectGroup"/>
        </div>
        <div class="col-md-4" v-show="currentView !='main-consolidated' || (!params.isReport && !params.isFilterReport)">
            <!-- Depende de group-->
            <select-period :objectInput="objectToSelectPeriod"/>
        </div>
        <div class="col-md-4" v-show="currentView =='main-consolidated' && params.isReport && !params.isAreas">
            <div class="form-group" style="padding-top:24px;">
                <a href="#" @click="emitEvent('save-report-asignatures')" class="btn btn-success btn-sm btn-block">
                    GUARDAR INFORME DE ASIGNATURAS
                </a>
            </div>
        </div>

        <div class="col-md-2" v-show="currentView =='main-consolidated' && params.isFilterReport">
            <div class="form-group padding-at">
                <label for="condition">Condición</label>
                <select @change="emitEvent('default')" id="condition" class="form-control" v-model="objectToManagerGroupSelect.condition">
                    <option value="0"> A =  </option>
                    <option value="1"> A >= </option>
                    <option value="2"> A <= </option>
                </select>
            </div>
        </div>

        <div class="col-md-2" v-show="currentView =='main-consolidated' && params.isFilterReport">
            <div class="form-group padding-at">
                <label for="condition_number">Cantidad</label>
                <select @change="emitEvent('default')" id="condition_number" class="form-control" v-model="objectToManagerGroupSelect.condition_number">
                    <option v-for="num in 6" :value="num">{{num}}</option>
                </select>
            </div>
        </div>


        
        <div class="clearfix"></div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import SelectGrade from "../SelectGrade";
    import SelectGroup from "../SelectGroup";
    import SelectPeriod from "../SelectPeriod";
    import SelectPeriodSection from "../SelectPeriodSection";
    import SelectSubgroup from "../SelectSubgroup";

    export default {
        components: {
            SelectSubgroup,
            SelectPeriod, SelectPeriodSection,
            SelectGrade, SelectGroup
        },
        props: ["params", "object-input"],
        name: "manager-group-select",
        data() {
            return {
                objectToManagerGroupSelect: {
                    grade_id: 0,
                    group_id: 0,
                    periods_id: 1,
                    type: "",
                    whoTriggered: "",
                    isSubGroup: false,
                    condition_number: 3,
                    condition: "1",
                },
                objectToSelectGrade: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@' + this.objectInput.referenceId + '.grades',
                    referenceGetObjectSelected: 'get-object-grade-selected@' + this.objectInput.referenceId + '.grades'
                },
                objectToSelectGroup: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@' + this.objectInput.referenceId + '.group',
                    referenceGetObjectSelected: 'get-object-selected@' + this.objectInput.referenceId + '.group',
                    referenceToReciveObjectSelected: 'to-receive-object-selected@' + this.objectInput.referenceId + '.group',
                    id: 0
                },
                objectToSelectPeriod: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@' + this.objectInput.referenceId + '.periods',
                    referenceGetObjectSelected: 'get-object-selected@' + this.objectInput.referenceId + '.periods',
                    referenceToReciveObjectSelected: 'to-receive-object-selected@' + this.objectInput.referenceId + '.periods',
                    id: 1
                },
            }
        },
        created() {
            this.managerEvents()
        },

        computed: {
            ...mapState([
                'currentView',
            ]),

        },
        methods: {
            getPeriodsByWorkingDay(object) {
                this.$store.dispatch('periodsByWorkingDayIns', {
                    workingdayid: object.working_day_id,
                    isGroup: true
                })
            },
            managerEvents() {

                this.$bus.$on(this.objectToSelectGrade.referenceGetObjectSelected, object => {
                    this.objectToManagerGroupSelect.grade_id = object.id
                    //this.$bus.$emit(this.objectToSelectSubGroup.referenceEmitObjectGradeSelected, object);
                    this.$bus.$emit(this.objectToSelectGroup.referenceToReciveObjectSelected, object);
                })

                this.$bus.$on(this.objectToSelectGroup.referenceGetObjectSelected, object => {
                    this.objectToSelectGroup.id = object.id
                    this.objectToManagerGroupSelect.group_id = object.id
                    this.getPeriodsByWorkingDay(object);
                    this.objectToManagerGroupSelect.whoTriggered = ""
                    this.$bus.$emit(this.objectToSelectPeriod.referenceToReciveObjectSelected, object);
                    if (this.objectToManagerGroupSelect.periods_id != 0 || this.currentView=='main-reprobated-final') {
                        this.objectToManagerGroupSelect.type = "group"

                        this.$bus.$emit(this.objectInput.referenceToReciveObjectSelected, this.objectToManagerGroupSelect);
                    }
                })

                this.$bus.$on(this.objectToSelectPeriod.referenceGetObjectSelected, objectId => {
                    this.objectToSelectPeriod.id = objectId
                    this.objectToManagerGroupSelect.periods_id = objectId
                    this.objectToManagerGroupSelect.whoTriggered = "period"
                    this.$bus.$emit(this.objectInput.referenceToReciveObjectSelected, this.objectToManagerGroupSelect);
                })
            },
            emitEvent(text){
                this.objectToManagerGroupSelect.whoTriggered = text
                this.$bus.$emit(this.objectInput.referenceToReciveObjectSelected, this.objectToManagerGroupSelect);
            }
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