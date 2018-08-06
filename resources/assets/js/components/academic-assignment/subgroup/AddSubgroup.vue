<template>
    <form @submit="checkForm" action="">

        <div class="row">
            <div class="col-md-6">
                <select-grade :objectInput="objectToSelectGrade"></select-grade>
            </div>
            <div class="col-md-6">

            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <select-areas :objectInput="objectToSelectAreas"></select-areas>
                <!--<at-select :type="{name:'Área', nameEv:'area-add', tby:'null', validate:true}"
                            :data="areas"></at-select>-->
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <multi-select
                            :type="{name:'Asignatura', nameEv:'asignature-add-subgroup', tby:'null', validate:true}"
                            :data="asignatures"></multi-select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <select-teacher :objectInput="objectToSelectTeachers"></select-teacher>
                </div>
            </div>
            <div class="col-md-3">
                <at-select :type="{name:'Tipo', nameEv:'subjetcType-add', tby:'null', id:2, validate:true}"
                           :data="subjectsType"></at-select>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Enumerar</label>
                    <select v-on:change="" class="form-control" name="" v-model="objectToSubgroup.order">
                        <option :value="false">Seleccionar</option>
                        <option v-for="n in 50" :value="n">
                            {{ n }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table-box :objectInput="objectToTableBox"></table-box>
            </div>
            <div class="col-md-12">
                <div class="form-group" v-show="objectToSubgroup.grade_id">
                    <input type="submit" value="Submit" class="btn btn-primary btn-block">
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import {mapState} from 'vuex'
    import SelectGrade from "../../partials/Form/SelectGrade"
    import SelectSubgroup from "../../partials/Form/SelectSubgroup";
    import TableBox from "../../partials/Form/SelectsBox/TableBox";
    import MultiSelect from "../../partials/MultiSelect";
    import AtSelect from "../../partials/AtSelect";
    import SelectAreas from "../../partials/Form/SelectAreas";
    import SelectTeacher from "../../partials/Form/SelectTeacher";

    export default {
        name: "add-subgroup",
        components: {
            SelectTeacher,
            SelectAreas,
            AtSelect,
            MultiSelect,
            TableBox,
            SelectSubgroup,
            SelectGrade,
        },
        data() {
            return {
                objectToSubgroup: {
                    grade_id: 0,
                    areas_id: 0,
                    teacher_id: 0,
                    subgroup_type_id:2,
                    order: 0,
                    asignatures: [],
                    subgroups: [],
                    data: []
                },
                objectToSelectGrade: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@subgroup.grades',
                    referenceGetObjectSelected: 'get-object-grade-selected@subgroup.grades'
                },
                objectToSelectAreas: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@subgroup.areas',
                    referenceGetObjectSelected: 'get-object-area-selected@subgroup.areas'
                },
                objectToSelectTeachers: {
                    referenceChangeFormSelect: 'get-event-change-of-form-select@subgroup.teachers',
                    referenceGetObjectSelected: 'get-object-area-selected@subgroup.teachers'
                },
                objectToTableBox: {
                    name: "SUBGRUPOS"
                },
                objectToAsignatures: {
                    asignatures: []
                }
            }
        },
        created() {
            this.managerEvents()
        },
        mounted() {

        },
        computed: {
            ...mapState([
                'grades',
                'areas',
                'asignatures',
                'subjectsType',
                'teachers'
            ]),
        },
        methods: {
            managerEvents() {

                this.$bus.$on(this.objectToSelectGrade.referenceGetObjectSelected, object => {
                    this.$bus.$emit("to-receive-grade-selected", object);
                    this.objectToSubgroup.grade_id = object.id
                })

                this.$bus.$on(this.objectToSelectAreas.referenceGetObjectSelected, object => {
                    this.objectToSubgroup.areas_id = object.id
                })

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

            },
            checkForm(e) {

                if (this.objectToSubgroup.grade_id) {
                    if (this.objectToSubgroup.areas_id) {
                        if (this.objectToSubgroup.asignatures.length > 0) {
                            this.$bus.$emit('i-can-search-dirtybox')
                        } else {
                            this.$swal({
                                position: 'top-end',
                                type: 'info',
                                title: 'Seleccione una asignatura',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }

                    } else {
                        this.$swal({
                            position: 'top-end',
                            type: 'info',
                            title: 'Seleccione un area',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }

                } else {
                    this.$swal({
                        position: 'top-end',
                        type: 'info',
                        title: 'Seleccione un área',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
                e.preventDefault();
            },

            setStorePensum(arrayDirtys) {

                this.data = [];
                this.objectToSubgroup.subgroups = []
                this.objectToSubgroup.subgroups = arrayDirtys

                this.objectToSubgroup.asignatures.forEach((asignature) => {

                    this.objectToSubgroup.subgroups.forEach((row) => {

                        this.data.push(
                            {
                                percent: row.valuePercent,
                                ihs: row.valueIhs,
                                order: this.objectToSubgroup.order,
                                sub_group_id: row.id,
                                teacher_id: this.objectToSubgroup.teacher_id == 0 ? null : this.objectToSubgroup.teacher_id,
                                areas_id: this.objectToSubgroup.areas_id,
                                subjects_type_id: this.objectToSubgroup.subgroup_type_id,
                                asignatures_id: asignature.id
                            }
                        );
                    })
                })

                if (this.data.length != 0) {
                    this.objectToSubgroup.subgroups.forEach((grade) => {
                        grade.valuePercent = -1;
                        grade.valueIhs = -1;
                    })

                    this.sendData(this.data)
                }
            },

            sendData(dataSubgroup) {
                let data = dataSubgroup
                let _this = this
                axios.post('storeSubGroupPensumByGroup', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            if (response.data != 0) {
                                _this.$swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'LISTO',
                                    showConfirmButton: false,
                                    timer: 2000
                                })
                            } else {
                                _this.$swal({
                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Error',
                                    showConfirmButton: false,
                                    timer: 2000
                                })
                            }
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css">

</style>