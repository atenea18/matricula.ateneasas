<template>

    <div>
        <div class="row">
            <div class="col-md-3">
                <select-grade :objectInput="objectToSelectGrade"></select-grade>
            </div>
            <div class="col-md-3">
                <select-subgroup :objectInput="objectToSelectSubGroup" ></select-subgroup>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>ÁREAS </th>
                    </tr>

                    </thead>
                    <tbody>
                    <tr v-for="(areas,i) in objectToTableSubgroup.arrayData">
                        <td>{{i+1}}</td>
                        <td>
                            {{areas.name_area}}
                            <br>
                            <ul>
                                <li v-for="asignature in areas.asignatures">
                                    {{asignature.name_asignatures}}
                                </li>
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</template>

<script>


    import SelectGrade from "../../partials/Form/SelectGrade";
    import SelectSubgroup from "../../partials/Form/SelectSubgroup";

    export default {
        name: "table-subgroup-pensum",
        components: {
            SelectSubgroup,
            SelectGrade},
        data() {
            return {
                objectToTableSubgroup:{
                    grade_id:0,
                    sub_group_id:0,
                    arrayData:[]
                },
                identification: "table-subgroup-pensum",
                objectToSelectGrade: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@'+this.identification+'.grades',
                    referenceGetObjectSelected: 'get-object-grade-selected@'+this.identification+'.grades'
                },
                objectToSelectSubGroup:{
                    referenceChangeFormSelect: 'get-event-change-of-form-select@'+this.identification+'.subgroup',
                    referenceGetObjectSelected: 'get-object-selected@'+this.identification+'.subgroup',
                    referenceEmitObjectGradeSelected: 'set-emit-object-grade-selected@'+this.identification+'.subgroup',
                }
            }
        },
        created(){
            this.managerEvents()
        },
        methods: {
            managerEvents() {

                this.$bus.$on(this.objectToSelectGrade.referenceGetObjectSelected, object => {
                    this.$bus.$emit(this.objectToSelectSubGroup.referenceEmitObjectGradeSelected, object);
                    this.objectToTableSubgroup.grade_id = object.id
                })

                this.$bus.$on(this.objectToSelectSubGroup.referenceGetObjectSelected, object => {
                    this.objectToTableSubgroup.sub_group_id = object.id
                    this.getSubGroupPensum(this.objectToTableSubgroup)
                })

                /*
                this.$bus.$on(this.objectToSelectTeachers.referenceGetObjectSelected, object => {
                    this.objectToSubgroup.teacher_id = object.id
                })

                this.$bus.$on('selected-values-asignature-add-subgroup', (values) => {
                    this.objectToSubgroup.asignatures = values
                })

                this.$bus.$on('i-can-to-receive-subgroups', (subgroups) => {
                    this.setStorePensum(subgroups)
                })

                this.$bus.$on('selected-id-subjetcType-add', (id) => {
                    this.objectToSubgroup.subgroup_type_id = id
                })
                */
            },
            getSubGroupPensum(object){
                let params = {
                    sub_group_id: object.sub_group_id
                }

                axios.get('getSubgroupPensum', {params}).then(res => {
                    this.objectToTableSubgroup.arrayData = res.data;
                    console.log(this.objectToTableSubgroup.arrayData)
                })
            }
        },
    }
</script>

<style>
    .title-area {
        font-size: 13px;
    }

    .table-custom h2,
    .table-custom th {
        font-size: 13px !important;
    }
</style>