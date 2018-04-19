<template>
    <form  @submit="checkForm" action="">

        <div class="row">
            <div class="col-md-6">
                <select-grade :objectInput="objectToSelectGrade"></select-grade>
            </div>
            <div class="col-md-6">
                <select-subgroup></select-subgroup>
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
                    <multi-select :type="{name:'Asignatura', nameEv:'asignature-add', tby:'null', validate:true}"
                                   :data="asignatures"></multi-select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <select-teacher :objectInput="objectToSelectTeachers"></select-teacher>
                    <!--<at-select :type="{name:'un Docente', nameEv:'teacher-add', tby:'null'}" :data="teachers"></at-select>-->
                </div>
            </div>
            <div class="col-md-3">
                <at-select :type="{name:'Tipo', nameEv:'subjetcType-add', tby:'null', id:1, validate:true}"
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
            <!--<div v-show="isSend" class="col-md-12 alert-info" style="text-align: center; padding: 5px;">
                <p>CARGANDO</p>
            </div>
            <div v-show="isResponse" class="col-md-12 alert-info" style="text-align: center; padding: 5px;">
                <p>{{message}}</p>
            </div>
            -->
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
                    grade_id:0,
                    areas_id:0,
                    teacher_id:0,
                    order:0
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
                    name:"SUBGRUPOS"
                }
            }
        },
        created(){
          this.managerEvents()
        },
        mounted(){

        },
        computed:{
            ...mapState([
                'grades',
                'areas',
                'asignatures',
                'subjectsType',
                'teachers'
            ]),
        },
        methods:{
            managerEvents(){

                this.$bus.$on(this.objectToSelectGrade.referenceGetObjectSelected, object => {
                    //to-receive-grade-selected: Evento emitido para que table-box liste los subgrupos
                    this.$bus.$emit("to-receive-grade-selected", object);
                    this.objectToSubgroup.grade_id = object.id
                })

                this.$bus.$on(this.objectToSelectAreas.referenceGetObjectSelected, object => {
                    console.log(object)
                    this.objectToSubgroup.areas_id = object.id
                })

                this.$bus.$on(this.objectToSelectTeachers.referenceGetObjectSelected, object => {
                    console.log(object)
                    this.objectToSubgroup.areas_id = object.id
                })

            },
            checkForm(e) {

                if (this.objectToSubgroup.grade_id) {
                    if (this.objectToSubgroup.areas_id) {
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
                        title: 'Seleccione un área',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
                e.preventDefault();
            },
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css">

</style>