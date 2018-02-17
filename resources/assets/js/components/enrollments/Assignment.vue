<template>
    <div>
        <div class="form-group">
            <label for="">Seleccione un grupo</label>
            <select v-on:change="" class="form-control" name="" id="" v-model="idGroupSelect">
                <option value="0">Seleccionar</option>
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
                    <input type="radio" :name="nameOption" id="optionsRadios1" value="1" v-model="picked">
                    Incluir
                </label>
                <label>
                    <input type="radio" :name="nameOption" id="" value="2" checked>
                    Excluir
                </label>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-block" @click="assignment"> Asignar</button>
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
            checksTrue: {type: Array}
        },
        data() {
            return {
                idGroupSelect: 0,
                picked:null
            }
        },
        methods: {
            assignment: function(){
                console.log(this.checksTrue);
            }
        }

    }
</script>

<style scoped>

</style>