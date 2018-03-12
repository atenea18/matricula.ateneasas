<template>
    <form @submit="checkForm" action="">

        <h5> {{title}}</h5>

        <div class="row">
            <div class="col-md-6">
                <at-select :type="{name:'Grado', nameEv:'grade-add', tby:'null', validate:true}"
                           :data="grades"></at-select>
            </div>
            <div class="col-md-6">
                <div class="form-group" style="padding-top: 25px">
                    <a href="#" @click="setCopyPensum" class="btn btn-default btn-block">COPIAR PENSUM</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <at-select :type="{name:'Área', nameEv:'area-add', tby:'null', validate:true}"
                           :data="areas"></at-select>
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
                    <at-select :type="{name:'un Docente', nameEv:'teacher-add', tby:'null'}" :data="teachers"></at-select>
                </div>
            </div>
            <div class="col-md-3">
                <at-select :type="{name:'Tipo', nameEv:'subjetcType-add', tby:'null', id:1, validate:true}"
                           :data="subjectsType"></at-select>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Enumerar</label>
                    <select v-on:change="" class="form-control" name="" v-model="order">
                        <option :value="false">Seleccionar</option>
                        <option v-for="n in 20" :value="n">
                            {{ n }}
                        </option>
                    </select>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <at-box-data ref="box"
                             :type="{name:'Tabla de Grupos', nameEv:'groupadd', url:'groupsByGrade', tby:'grade-add'}"></at-box-data>
            </div>
            <div class="col-md-12">
                <div class="form-group" v-show="assignmentGroup.idgrade">
                    <input type="submit" value="Submit" class="btn btn-primary btn-block">

                </div>
            </div>
            <div v-show="isSend" class="col-md-12 alert-info" style="text-align: center; padding: 5px;">
                <p>CARGANDO</p>
            </div>
            <div v-show="isResponse" class="col-md-12 alert-info" style="text-align: center; padding: 5px;">
                <p>{{message}}</p>
            </div>
        </div>
    </form>
</template>
<script>
    import {mapState, mapMutations, mapGetters} from 'vuex';
    import AtSelectBy from '../partials/AtSelectBy'
    import MultiSelect from '../partials/MultiSelect';
    import AtBoxData from '../partials/BoxData/AtBoxData';
    import AtSelect from '../partials/AtSelect';
    import BoxGrade from './BoxGrade';

    export default {
        name: "add-group",
        components: {
            MultiSelect,
            BoxGrade,
            AtSelect,
            AtSelectBy,
            AtBoxData
        },
        props: {},
        data() {
            return {
                assignmentGroup: {
                    idgrade: 0,
                    idgroup: 0,
                    idarea: 0,
                    asignatures: [],
                    idsubjectsType: 1,
                    idteacher: 0,
                    order: 0
                },
                title: '',
                order: 1,
                message: '',
                value: [],
                isSend: false,
                selected: null,
                isMultiple: false,
                isResponse: false,
                idSubjectsType: 1,
                textButton: "Adiccionar",
                selectedIdAreaOrAsignature: 0,
                dataAsignatures: 0,
                arrayDataPensum: [],
                arrayDirtyBoxGrade: [],
                idSelectedGrade: 0
            }
        },
        computed: {
            ...mapState([
                'grades',
                'areas',
                'asignatures',
                'subjectsType',
                'teachers'
            ]),
        }
        ,
        created() {
            this.listiningChild();
            this.setting();

            this.$bus.$on('set-send-at-box', (arrayD) => {
                this.setStorePensum(arrayD)
            })
        },


        methods: {

            listiningChild() {
                this.$bus.$on('selected-id-grade-add', (id) => {
                    this.assignmentGroup.idgrade = id
                });
                this.$bus.$on('selected-id-area-add', (id) => {
                    this.assignmentGroup.idarea = id
                })
                this.$bus.$on('selected-id-group-add', (id) => {
                    this.assignmentGroup.idgroup = id
                })
                this.$bus.$on('selected-id-subjetcType-add', (id) => {

                    this.assignmentGroup.idsubjectsType = id
                })
                this.$bus.$on('selected-values-asignature-add', (values) => {
                    this.assignmentGroup.asignatures = values
                })
                this.$bus.$on('selected-id-teacher-add', (id) => {
                    this.assignmentGroup.idteacher = id
                })
            },

            checkForm(e) {

                if (this.assignmentGroup.idarea) {
                    if (this.assignmentGroup.asignatures.length != 0) {
                        this.$bus.$emit('set-send', this.assignmentGroup.idgrade)
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

            setCopyPensum() {
                console.log(this.teachers)

                if (this.assignmentGroup.idgrade) {
                    let data = {
                        grade_id: this.assignmentGroup.idgrade
                    }

                    let _this = this

                    axios.post('copyPensumByGrade', {data})
                        .then(function (response) {
                            if (response.status == 200) {
                                _this.$swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'LISTO',
                                    showConfirmButton: false,
                                    timer: 2000
                                })
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
                else {

                }
            },
            setStorePensum(arrayDirtys) {
                this.arrayDataPensum = [];
                this.arrayDirtyBoxGrade = [];

                this.arrayDirtyBoxGrade = arrayDirtys


                this.assignmentGroup.asignatures.forEach((asignature) => {
                    this.arrayDirtyBoxGrade.forEach((row) => {
                        this.arrayDataPensum.push(
                            {
                                percent: row.valuePercent,
                                ihs: row.valueIhs,
                                order: this.order,
                                group_id: row.id,
                                teacher_id: this.assignmentGroup.idteacher==0?null:this.assignmentGroup.idteacher,
                                areas_id: this.assignmentGroup.idarea,
                                subjects_type_id: this.assignmentGroup.idsubjectsType,
                                asignatures_id: asignature.id
                            }
                        );
                    })
                })

                if (this.arrayDataPensum.length != 0) {
                    this.arrayDirtyBoxGrade.forEach((grade) => {
                        grade.valuePercent = -1;
                        grade.valueIhs = -1;
                    })

                    console.log(this.arrayDataPensum)

                    this.sendData(this.arrayDataPensum, 'storePensumByGroup')
                }


            },


            sendData: function (data, url) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let _this = this;
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {data},
                    success: function (response) {
                        console.log(response)

                        _this.isSend = false;
                        if (response != 0) {
                            _this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'LISTO',
                                showConfirmButton: false,
                                timer: 2000
                            })
                            _this.order = 1;
                            _this.idSubjectsType = 1;


                        } else {
                            _this.$swal({
                                position: 'top-end',
                                type: 'error',
                                title: 'Error',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }

                        _this.isResponse = true;

                        setTimeout(function () {
                            _this.isResponse = false;
                        }, 2500);

                    }
                });

            },


            setting: function () {


            },


        },

    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css">

    #title {

    }
</style>