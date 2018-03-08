<template>
    <form @submit="checkForm" action="">

        <h5> {{title}}</h5>

        <div class="row">
            <div class="col-md-6">
                <at-select :type="{name:'Grado', nameEv:'grade', tby:'null', validate:true}"
                           :data="grades"></at-select>
            </div>
            <div class="col-md-6">
                <at-select :type="{name:'Área', nameEv:'area', tby:'null', validate:true}"
                           :data="areas"></at-select>
                <!--
                <at-select-by
                        :type="{name:'un Grupo', nameEv:'group', url:'groupsByGrade', tby:'grade'}"></at-select-by>
                        -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <at-select :type="{name:'Tipo', nameEv:'subjetcType', tby:'null', id:1, validate:true}" :data="subjectsType"></at-select>
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
            <div class="col-md-6">
                <div class="form-group">
                    <multi-select :type="{name:'Asignatura', nameEv:'asignature', tby:'null', validate:true}"
                                  :data="asignatures"></multi-select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <at-select :type="{name:'un Docente', nameEv:'teacher', tby:'null'}" :data="[]"></at-select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" style="padding-top: 25px">
                    <a href="#" class="btn btn-default">COPIAR PENSUM</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <at-box-data
                        :type="{name:'Tabla de Grupos', nameEv:'group', url:'groupsByGrade', tby:'grade'}"></at-box-data>
            </div>
            <div class="col-md-12">
                <div class="form-group">
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
                    idsubjectsType: 0,
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
        created() {
            this.listiningChild();
            this.setting();
        },

        computed: {
            ...mapState(['grades', 'areas', 'asignatures', 'subjectsType']),
        }
        ,
        methods: {
            listiningChild() {
                this.$bus.$on('selected-id-grade', (id) => {
                    this.assignmentGroup.idgrade = id
                });
                this.$bus.$on('selected-id-area', (id) => {
                    this.assignmentGroup.idarea = id
                })
                this.$bus.$on('selected-id-group', (id) => {
                    this.assignmentGroup.idgroup = id
                })
                this.$bus.$on('selected-id-subjectType', (id) => {
                    this.assignmentGroup.idsubjectsType = id
                })
                this.$bus.$on('selected-values-asignature', (values) => {
                    this.assignmentGroup.asignatures = values
                })
            },
            checkForm(e) {
                if (this.assignmentGroup.idgrade)
                    this.$swal(
                        'Good job!',
                        'You clicked the button!',
                        'success'
                    )
                e.preventDefault();
            },
            setStorePensum() {
                /*
                this.isSend = true;
                this.arrayDataPensum = [];
                this.arrayDirtyBoxGrade = [];

                this.$refs.boxgroup.forEach((component) => {

                    if (component._data.valueIhs != "0" || component._data.valuePercent != "0") {
                        this.arrayDirtyBoxGrade.push(component._data);
                    }
                });

                this.value.forEach((asignature) => {
                    this.arrayDirtyBoxGrade.forEach((grade) => {
                        this.arrayDataPensum.push(
                            {
                                percent: grade.valuePercent,
                                ihs: grade.valueIhs,
                                order: this.order,
                                grade_id: grade.idGrade,
                                areas_id: this.selectedIdAreaOrAsignature,
                                subjects_type_id: this.idSubjectsType,
                                asignatures_id: asignature.id
                            }
                        );
                    })
                })
                this.arrayDirtyBoxGrade.forEach((grade) => {
                    grade.valuePercent = 0;
                    grade.valueIhs = 0;
                })

                //console.log(this.arrayDataPensum);
                if (this.arrayDataPensum.length != 0)

                    this.sendData(this.arrayDataPensum, 'storePensum')
                    */
            },

            sendData: function (data, url) {
                /*
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


                        _this.isSend = false;
                        if (response != 0) {
                            _this.message = "REGISTRO EXITOSO"
                            _this.$bus.$emit('reload-grade', this)
                            _this.value = [];
                            _this.order = 1;
                            _this.idSubjectsType = 1;
                            //_this.selectedIdAreaOrAsignature = 0;

                        } else {
                            _this.message = "REGISTRO EXISTENTE"
                        }

                        _this.isResponse = true;

                        setTimeout(function () {
                            _this.isResponse = false;
                        }, 2500);

                    }
                });
                */
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