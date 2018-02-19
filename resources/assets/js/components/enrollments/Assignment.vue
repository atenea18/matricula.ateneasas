<template>
    <div>

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
        <div class="form-group">
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
        <p v-show="isSend">Cargando..</p>
        <div class="form-group">
            <button v-show="idGroupSelect" class="btn btn-primary btn-block" @click="assignment"> Asignar</button>
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
                this.isSend = true;
                if (this.typeQuery == "UPDATE") {

                    this.updateEnrollment();
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
                    data = JSON.stringify(newArray);
                    this.sendData(data)
                }

            },
            sendData: function (data) {

                //console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let _this = this;
                $.ajax({
                    type: "POST",
                    url: 'groupupdate',
                    data: {data},
                    beforeSend: function(xhr){

                    },
                    success: function (response) {
                        this.isSend = false;
                        console.log(response);
                        _this.$bus.$emit('reload-enroll', null)
                    }
                });

            }

        }

    }
</script>

<style scoped>

</style>