<template>

    <div>
        <hr>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Seleccione grupo a asignar</label>
                <select v-on:change="" class="form-control" name="" id="" v-model="idGroupSelect">
                    <option :value="0"> Seleccione un grupo </option>
                    <option v-for="group in groups" :value="group.id">
                        {{ group.name}}
                        <small>{{group.headquarter_name}}</small>
                    </option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group controls-center">
                <label for="">Seleccione operación</label>
                <div class="radio">
                    <label style="margin-right: 20px">
                        <input type="radio" :name="nameOption" value="1" v-model="picked" checked>
                        Incluir
                    </label>
                    <label>
                        <input type="radio" :name="nameOption" value="2" v-model="picked">
                        Excluir
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group" style="padding-top: 20px; ">
                <button v-show="idGroupSelect" class="btn btn-primary btn-block" @click="assignment"> Asignar</button>
            </div>
        </div>
        <div v-show="isSend" class="col-md-12 alert-info" style="text-align: center; padding: 5px;">
            <p >Cargando..</p>
        </div>


    </div>
</template>

<script>
    export default {
        name: "assignment",
        props: {
            groups: {type: Array},
            nameOption: {type: String},
            checksFalse: {type: Array},
            checksTrue: {type: Array},
            typeQuery: {type: String}
        },
        data() {
            return {
                idGroupSelect: 0,
                picked: "1",
                isSend: false,
            }
        },
        methods: {
            assignment: function () {

                if (this.typeQuery == "UPDATE") {

                    this.updateEnrollment();
                }
                if (this.typeQuery == "INSERT") {

                    this.insertEnrollment();
                }
            },
            updateEnrollment: function () {

                let data = "";
                let newArray = [];
                if (this.picked == "1") {
                    newArray = this.checksTrue.filter(Boolean)
                    newArray.forEach(element => {
                        element.group_id = this.idGroupSelect;
                    });
                } else {
                    newArray = this.checksFalse.filter(Boolean)
                    newArray.forEach(element => {
                        element.group_id = this.idGroupSelect;
                    });
                }
                if(newArray.length){
                    this.isSend = true;
                    data = JSON.stringify(newArray);
                    this.sendData(data,'groupupdate')
                }

            },
            insertEnrollment: function(){
                let data = "";
                let newArray = [];
                if (this.picked == "1") {
                    newArray = this.checksTrue.filter(Boolean)
                } else {
                    newArray = this.checksFalse.filter(Boolean)
                }

                newArray.forEach(element => {
                    element.group_id = this.idGroupSelect;
                });

                if(newArray.length){
                    this.isSend = true;
                    data = JSON.stringify(newArray);
                    console.log(data);
                    this.sendData(data,'groupinsert')
                }

            },
            sendData: function (data,url) {

                //console.log(data);
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
                    beforeSend: function(xhr){

                    },
                    success: function (response) {
                        _this.isSend = false;
                        console.log(response);
                        _this.$bus.$emit('reload-enroll', _this.idGroupSelect)
                    }


                });
            },
        },


    }
</script>

<style scoped>
    .controls-center{
        text-align: center;
        padding: 4px auto;

    }
</style>