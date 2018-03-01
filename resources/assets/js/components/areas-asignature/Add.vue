<template>
    <form action="">
        <h5> {{title}}</h5>
        <div class="alert-info" v-show="isResponse">
            <p style="text-align: center; padding: 5px;">{{message}}</p>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Seleccionar</label>
                <select v-on:change="getNameSelectedAreaOrAsignature" class="form-control" name=""
                        v-model="selectedIdAreaOrAsignature">
                    <option :value="0">Seleccionar</option>
                    <option v-for="data in dataAreasOrAsignatures" :value="data.id">
                        {{ data.name}}
                    </option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div v-show="selectedIdAreaOrAsignature" class="form-group">
                <label for="">Personalizar nombre</label>
                <input type="text" class="form-control" v-model="nameCustom">
            </div>
        </div>
        <div class="col-md-3">
            <div v-show="selectedIdAreaOrAsignature" class="form-group">
                <label for="">Seleccionar Tipo</label>
                <select v-on:change="reloadMultiselect" class="form-control" name="" id="" v-model="idSubjectsType">
                    <option :value="0">Seleccionar</option>
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
        <div class="col-md-5">
            <div v-show="selectedIdAreaOrAsignature" class="form-group">
                <div v-if="idSubjectsType">
                    <label for="">{{textMultiselect}}</label>
                    <multiselect
                            v-model="value"
                            :options="options"
                            :multiple="isMultiple"
                            track-by="custom_name"
                            :custom-label="customLabel">
                    </multiselect>

                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div v-show="selectedIdAreaOrAsignature" class="form-group">
                <a class="btn btn-default btn-block" style="display: inline-block; margin-top: 26px"
                   @click="reloadMultiselect">Reload</a>
            </div>
        </div>
        <div class="col-md-12">
            <div v-show="selectedIdAreaOrAsignature" class="form-group">
                <a class="btn btn-primary btn-block" v-show="idSubjectsType" @click="setCustomAreasOrAsignatures">{{textButton}}</a>
            </div>
        </div>

    </form>
</template>

<script>
    import Multiselect from 'vue-multiselect';

    export default {
        name: "add",
        components: {Multiselect},
        props: {
            title: {type: String},
            typeAreaOrAsignatures: {type: String},
            typeComponent: {type: String}
        },
        data() {
            return {

                num: 0,
                nameCustom: '',
                order: 1,
                message: '',
                value: null,
                isSend: false,
                options: [],
                selected: null,
                isMultiple: true,
                isResponse: false,
                idSubjectsType: 2,
                subjectsType: null,
                textButton: "Adiccionar",
                dataAreasOrAsignatures: null,
                selectedIdAreaOrAsignature: 0,
                textMultiselect: "",
                customData: 0,
                arrayAreasAndAsignature: []
            }
        },
        methods: {
            customLabel(option) {
                return `${option.custom_name}`
            },
            setType: function () {
                this.options = this.customData == null ? [] : this.customData;
            },
            reloadMultiselect: function () {
                if (this.typeAreaOrAsignatures == "areas") {
                    this.getCustomToAsignment('getCustomAsignatures/' + this.idSubjectsType);
                } else {
                    this.getCustomToAsignment('getCustomAreas/' + this.idSubjectsType);
                }
                this.value = [];
            },
            getNameSelectedAreaOrAsignature: function () {
                this.dataAreasOrAsignatures.forEach(item => {
                    if (item.id == this.selectedIdAreaOrAsignature) {
                        this.nameCustom = item.name;
                    }
                });
            },
            setCustomAreasOrAsignatures() {
                let customAreasOrAsginatures = {
                    custom_name: this.nameCustom,
                    order: this.order,
                    area_or_asig_id: this.selectedIdAreaOrAsignature,
                    subjects_type_id: this.idSubjectsType
                }

                let data = JSON.stringify(customAreasOrAsginatures);

                if (this.typeAreaOrAsignatures == "areas") {
                    this.sendData(data, 'setCustomAreas')
                } else {
                    this.sendData(data, 'setCustomAsignatures')
                }
            },
            createObjectCustom(id) {
                if (this.typeAreaOrAsignatures == "areas") {
                    this.value.forEach((custom, i) => {
                        this.arrayAreasAndAsignature[i] = {
                            custom_areas_id: id,
                            custom_asignatures_id: custom.id
                        }
                    })
                } else {
                    if (this.value != null) {
                        this.arrayAreasAndAsignature[0] = {
                            custom_asignatures_id: id,
                            custom_areas_id: this.value.id
                        }
                    }

                }

                this.sendDataAreasAndAsignatures();
            },
            sendDataAreasAndAsignatures() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let _this = this;
                let data = this.arrayAreasAndAsignature;
                $.ajax({
                    type: "POST",
                    url: 'setAreasAndAsignatures',
                    data: {data},
                    success: function (response) {
                        _this.setting()
                        _this.$bus.$emit('reload-add', null)

                    }
                });
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
                        _this.isSend = false;
                        if (response != 0) {
                            _this.message = "Ok"
                            _this.selectedIdAreaOrAsignature = 0;
                            _this.createObjectCustom(parseInt(response));
                            _this.value = [];

                        } else {
                            _this.message = "Registro Duplicado"
                        }
                        _this.isResponse = true;
                        setTimeout(function () {
                            _this.isResponse = false;
                        }, 2000);
                    }
                });
            },

            setting: function () {
                //Update or Insert
                if (this.typeComponent == "update") {
                    this.textButton = "Actualizar";
                } else {

                }
                // Areas or Asignatures
                if (this.typeAreaOrAsignatures == "areas") {
                    this.getDataAreasOrAsignatures('getAreas');
                    this.getCustomToAsignment('getCustomAsignatures/' + this.idSubjectsType);
                    this.isMultiple = true;
                    this.textMultiselect = "Seleccionar Asignaturas"
                } else {
                    this.getDataAreasOrAsignatures('getAsignatures');
                    this.getCustomToAsignment('getCustomAreas/' + this.idSubjectsType);
                    this.isMultiple = false;
                    this.textMultiselect = "Seleccionar Área"
                }
            },
            getDataAreasOrAsignatures: function (method) {
                axios.get(method).then(res => {
                    this.dataAreasOrAsignatures = res.data;
                });
            },
            getCustomToAsignment(method) {
                axios.get(method).then(res => {
                    this.customData = res.data;
                    this.setType();
                });
            }

        },
        created() {
            this.setting();

            axios.get('getSubjectsType').then(res => {
                this.subjectsType = res.data;
            });
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css">

</style>