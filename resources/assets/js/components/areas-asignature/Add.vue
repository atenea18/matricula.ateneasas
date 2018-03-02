<template>
    <form action="">
        <h5> {{title}}</h5>
        <div class="alert-info" v-show="isResponse">
            <p style="text-align: center; padding: 5px;">{{message}}</p>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Seleccionar Área</label>
                <select v-on:change="" class="form-control" name=""
                        v-model="selectedIdAreaOrAsignature">
                    <option :value="0">Seleccionar</option>
                    <option v-for="data in dataAreas" :value="data.id">
                        {{ data.name}}
                    </option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div v-show="selectedIdAreaOrAsignature" class="form-group">
                <div v-if="idSubjectsType">
                    <label for="">Seleccionar Asignatura</label>
                    <multiselect
                            v-model="value"
                            :options="options"
                            :multiple="isMultiple"
                            track-by="name"
                            :custom-label="customLabel">
                    </multiselect>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div v-show="selectedIdAreaOrAsignature" class="form-group">
                <label for="">Seleccionar Tipo</label>
                <select v-on:change="" class="form-control" name="" id="" v-model="idSubjectsType">
                    <option v-for="type in subjectsType" :value="type.id">
                        {{ type.name}}
                    </option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div v-show="selectedIdAreaOrAsignature" class="form-group">
                <label for="">Enumerar</label>
                <select v-on:change="" class="form-control" name="" v-model="order">
                    <option :value="false">Seleccionar</option>
                    <option v-for="n in 20" :value="n">
                        {{ n }}
                    </option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td v-for="grade in dataGrades" style="padding: 2px;">
                        <box-grade ref="boxGrade" :grade="grade"></box-grade>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="col-md-12">
            <div v-show="selectedIdAreaOrAsignature" class="form-group">
                <a class="btn btn-primary btn-block" v-show="idSubjectsType" @click="setStorePensum">{{textButton}}</a>
            </div>
        </div>
    </form>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import BoxGrade from './BoxGrade';

    export default {
        name: "add",
        components: {Multiselect, BoxGrade},
        props: {},
        data() {
            return {
                title: '',
                order: 1,
                message: '',
                value: [],
                isSend: false,
                options: [],
                dataGrades: [],
                selected: null,
                isMultiple: true,
                isResponse: false,
                idSubjectsType: 1,
                subjectsType: [],
                textButton: "Adiccionar",
                dataAreas: null,
                selectedIdAreaOrAsignature: 0,
                dataAsignatures: 0,
                arrayDataPensum: [],
                arrayDirtyBoxGrade:[]
            }
        },
        methods: {
            customLabel(option) {
                return `${option.name}`;
            },
            setStorePensum() {
                this.arrayDataPensum = [];
                this.arrayDirtyBoxGrade = [];

                this.$refs.boxGrade.forEach((component) => {

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

                //console.log(this.arrayDataPensum);
                if(this.arrayDataPensum.length != 0)
                    this.sendData(this.arrayDataPensum,'storePensum')
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
                        console.log(response);
                        /*
                        _this.isSend = false;
                        if (response != 0) {
                            _this.message = "Ok"
                            _this.selectedIdAreaOrAsignature = 0;
                            _this.value = [];
                        } else {
                            _this.message = "Registro Duplicado"
                        }
                        _this.isResponse = true;
                        setTimeout(function () {
                            _this.isResponse = false;
                        }, 2000);
                        */
                    }
                });
            },

            setting: function () {
                this.getDataAreas();
                this.getDataAsignatures();
                this.getDataSubjectsType();
                this.getDataGrades();
                this.isMultiple = true;
            },
            getDataAreas: function () {
                axios.get('getAreas').then(res => {
                    this.dataAreas = res.data;
                });
            },
            getDataAsignatures() {
                axios.get('getAsignatures').then(res => {
                    this.options = res.data;
                });

            },
            getDataGrades(){
                axios.get('allgrades').then(res => {
                    this.dataGrades = res.data;
                });
            },
            getDataSubjectsType(){
                axios.get('getSubjectsType').then(res => {
                    this.subjectsType = res.data;
                });
            }
        },
        created() {
            this.setting();
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css">

    #title {

    }
</style>