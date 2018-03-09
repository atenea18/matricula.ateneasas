<template>
    <tr>
        <!-- Listar   -->
        <td v-show="isNotEdit">
            <small>{{dataAsignature.name_asignatures}}</small>
        </td>
        <td v-show="isNotEdit">
            <small>{{dataAsignature.subjects_type_name}}</small>
        </td>
        <td v-show="isNotEdit">
            <small>PESO: {{dataAsignature.percent}}</small>
        </td>
        <td v-show="isNotEdit">
            <small>IHS: {{dataAsignature.ihs}}</small>
        </td>
        <td v-show="isNotEdit">
            <small>ORDER: {{dataAsignature.order}}</small>
        </td>
        <td v-show="isNotEdit">
            <a href="#" @click="deleteAsignaturePensumBy(dataAsignature.id)"><i
                    class="fas fa-trash"></i></a>
            <a href="#" @click="editAsignaturePensumByGrade(dataAsignature.id)"><i
                    class="fas fa-pen-square"></i></a>
        </td>

        <!-- Editar  -->
        <td v-show="isEdit">
            <select v-on:change="" class="form-control" name="">
                <option>ASIGNATURA</option>
                <option v-for="type in 10">
                    Seleccione
                </option>
            </select>
        </td>
        <td v-show="isEdit">
            <select v-on:change="" class="form-control" name="">
                <option>TIPO</option>
                <option v-for="type in 10">
                    Seleccione
                </option>
            </select>
        </td>
        <td v-show="isEdit">
            <select v-on:change="" class="form-control" name="">
                <option>PESO</option>
                <option v-for="type in 10">
                    Seleccione
                </option>
            </select>
        </td>
        <td v-show="isEdit">
            <select v-on:change="" class="form-control" name="">
                <option>IHS</option>
                <option v-for="type in 10">
                    Seleccione
                </option>
            </select>
        </td>
        <td v-show="isEdit">
            <select v-on:change="" class="form-control" name="">
                <option>ORDER</option>
                <option v-for="type in 10">
                    Seleccione
                </option>
            </select>
        </td>
        <td v-show="isEdit" style="padding-top: 19px;">
            <a href="#" @click="editAsignaturePensumByGrade(dataAsignature.id)">
                <i style="font-size: 15px" class="fas fa-save"></i></a>
            <a href="#" @click="editAsignaturePensumByGrade(dataAsignature.id)">
                <i style="font-size: 15px" class="fas fa-undo-alt"></i>
            </a>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "at-row-asignatures",
        props: {
            dataAsignature: {type: Object},
            index: {type: Number},
            type: {type: Object}
        },
        data() {
            return {
                arrayDataToDelete: [],
                isNotEdit: true,
                isEdit: false
            }
        },
        methods: {
            deleteAsignaturePensumBy: function (id) {

                console.log(this.type.url+': '+id)

                this.arrayDataToDelete = {
                    id: id,
                    at_id: this.type.id
                }
                this.sendDataDelete();

            },
            editAsignaturePensumByGrade: function (id) {
                this.isNotEdit = !this.isNotEdit;
                this.isEdit = !this.isEdit;
            },
            sendDataDelete: function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let _this = this;
                let data = this.arrayDataToDelete;

                $.ajax({
                    type: "POST",
                    url: 'deleteAsignature'+this.type.url,
                    data: {data},
                    success: function (response) {
                        console.log(response);
                        _this.$bus.$emit('reload-asignatures', this)
                    }
                });
            },
        },
        data() {
            return {
                isEdit: false,
                isNotEdit: true
            }
        },
        created() {

        }
    }
</script>

<style scoped>

</style>