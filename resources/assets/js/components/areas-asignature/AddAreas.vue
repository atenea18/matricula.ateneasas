<template>
    <form action="">
        <h4 > Añadir un Área</h4>
        <div class="form-group">
            <label for="">Seleccionar Área</label>
            <select v-on:change="getAreas" class="form-control" name="" v-model="idArea">
                <option :value="0">Seleccionar</option>
                <option v-for="area in areas" :value="area.id">
                    {{ area.name}}
                </option>
            </select>
        </div>
        <div class="alert-info" v-show="isResponse">
            <p>{{message}} </p>
        </div>
        <div v-show="idArea">
            <div class="form-group">
                <label for="">Seleccionar Tipo</label>
                <select v-on:change="" class="form-control" name="" id="" v-model="idSubjectsType">
                    <option :value="0">Seleccionar</option>
                    <option v-for="type in subjectsType" :value="type.id">
                        {{ type.name}}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Personalizar nombre</label>
                <input type="text" class="form-control" v-model="nameArea">
            </div>
            <div class="form-group">
                <label for="">Enumerar</label>
                <select v-on:change="" class="form-control" name="" v-model="order">
                    <option :value="false">Seleccionar</option>
                    <option v-for="n in 20" :value="n">
                        {{ n }}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <a class="btn btn-default btn-block" v-show="idSubjectsType" @click="setCustomAreas">Adicionar</a>
            </div>
        </div>

    </form>
</template>

<script>
    export default {
        name: "add-areas",
        data() {
            return {
                nameArea: '',
                order: 1,
                idArea: 0,
                subjectsType: null,
                idSubjectsType: 0,
                areas: null,
                isSend: false,
                isResponse: false,
                message: ''

            }
        },
        methods: {
            getAreas: function () {
                this.areas.forEach(area => {
                    if (area.id == this.idArea) {
                        this.nameArea = area.name;
                    }
                })
                //console.log('getAreas')
            },
            setCustomAreas() {
                let custom_areas = {
                    custom_name: this.nameArea,
                    order: this.order,
                    areas_id: this.idArea,
                    subjects_type_id: this.idSubjectsType
                }
                console.log('yes')
                let data = JSON.stringify(custom_areas);

                this.sendData(data, 'setCustomAreas')
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
                    beforeSend: function (xhr) {

                    },
                    success: function (response) {
                        _this.isSend = false;
                        if (response != 0) {
                            _this.message = "Se adiciono correctamente"
                            _this.idArea = 0;
                            _this.$bus.$emit('reload-areas', null)
                        } else {
                            _this.message = "El área ya existe"
                        }
                        _this.isResponse = true;
                        setTimeout(function () {
                            _this.isResponse = false;
                        }, 3000);

                        //_this.$bus.$emit('reload-enroll', _this.idGroupSelect)
                    }


                });

            }

        },
        created() {
            axios.get('getAreas').then(res => {
                this.areas = res.data;
                console.log(res.data);
            });

            axios.get('getSubjectsType').then(res => {
                this.subjectsType = res.data;
                console.log(res.data);
            });


        }
    }
</script>

<style scoped>

</style>