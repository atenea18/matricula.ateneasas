<template>
    <tr style="font-size:11.5px">

        <td>
            <small>
                <input type="hidden" >
                <at-select :type="{name:'Asignatura', nameEv:'asignature-row'+dataAsignature.asignatures_id, id:dataAsignature.asignatures_id
            ,tby:'null', validate:true}" :data="asignatures"></at-select>
            </small>
        </td>
        <td>
            <small>
                <at-select :type="{name:'Tipo', nameEv:'subjetcType-row'+dataAsignature.asignatures_id, id:dataAsignature.subjects_type_id
            ,tby:'null', validate:true}" :data="subjectsType"></at-select>
            </small>

        </td>
        <td width="70px">
            <small>
                <label for="">%</label>
                <select v-on:change="setEdit({value:valuePercent, field:'percent', id:dataAsignature.id})" class="form-control"
                        v-model="valuePercent">
                    <option :value="0">0</option>
                    <option v-for="n in 100" :value="n">
                        {{ n }}
                    </option>
                </select>
            </small>
        </td>
        <td width="70px">
            <small>
                <label for="" >Ihs</label>
                <select v-on:change="setEdit({value:valueIhs, field:'ihs', id:dataAsignature.id})" class="form-control"
                        v-model="valueIhs">
                    <option :value="0">0</option>
                    <option v-for="n in 12" :value="n">
                        {{ n }}
                    </option>
                </select>
            </small>
        </td>
        <td width="70px">
            <small>
                <label for="">Orde</label>
                <select v-on:change="setEdit({value:order, field:'order', id:dataAsignature.id})"
                        class="form-control" name="" v-model="order">
                    <option :value="0">0</option>
                    <option v-for="n in 20" :value="n">
                        {{ n }}
                    </option>
                </select>
            </small>
        </td>
        <td>
            <small>
                <at-select
                        :type="{name:'un Docente', nameEv:'teacher-row'+dataAsignature.asignatures_id, id:dataAsignature.teacher_id ,tby:'null'}"
                        :data="teachers"></at-select>
            </small>
        </td>
        <td>
            <a href="#" @click="deleteAsignaturePensumBy(dataAsignature.id)"><i
                    class="fas fa-trash"></i></a>
        </td>

    </tr>
</template>

<script>
    import {mapState, mapMutations, mapGetters} from 'vuex';
    import AtSelect from '../AtSelect'

    export default {
        name: "at-row-asignatures",
        components: {
            AtSelect
        },
        computed: {
            ...mapState([
                'grades',
                'areas',
                'asignatures',
                'subjectsType',
                'teachers'
            ]),
        },
        props: {
            dataAsignature: {type: Object},
            index: {type: Number},
            type: {type: Object}
        },
        data() {
            return {
                arrayDataToDelete: [],
                isNotEdit: true,
                isEdit: false,
                valuePercent: 0,
                valueIhs: 0,
                order: 0,
                idpensum: this.dataAsignature.id
            }
        },


        methods: {
            listiningChild() {
                console.log(this.idpensum)
                this.$bus.$on('selected-id-asignatures-row' + this.dataAsignature.asignatures_id, (id) => {
                    let data = {value: id, field: 'asignature_id', id:this.idpensum}
                    this.setEdit(data)
                })
                this.$bus.$on('selected-id-subjetcType-row' + this.dataAsignature.asignatures_id, (id) => {
                    let data = {value: id, field: 'subjects_type_id', id:this.dataAsignature.id}
                    this.setEdit(data)

                })
                this.$bus.$on('selected-id-teacher-row' + this.dataAsignature.asignatures_id, (id) => {
                    let data = {value: id, field: 'teacher_id', id:this.dataAsignature.id}
                    this.setEdit(data)
                })
            },
            setEdit: function (pensum) {
                console.log(pensum)
                this.sendDataEdit(pensum)
            },

            sendDataEdit: function (dat) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let _this = this;
                let data = dat;

                $.ajax({
                    type: "POST",
                    url: 'editPensumGroup',
                    data: {data},
                    success: function (response) {
                        if (response != 0) {
                            _this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'LISTO',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }else{
                            _this.$swal({
                                position: 'top-end',
                                type: 'error',
                                title: 'Error',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        console.log(response);
                       // _this.$bus.$emit('reload-asignatures', this)
                    }
                });
            },

            deleteAsignaturePensumBy: function (id) {

                console.log(this.type.url + ': ' + id)

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
                    url: 'deleteAsignature' + this.type.url,
                    data: {data},
                    success: function (response) {
                        console.log(response);
                        _this.$bus.$emit('reload-asignatures', this)
                    }
                });
            },
        },
        created() {
            console.log(this.dataAsignature.id)
            this.$bus.$off('selected-id-asignature-row' + this.index)
            this.$bus.$off('selected-id-subjetcType-row' + this.index)
            this.$bus.$off('selected-id-teacher-row' + this.index)


            this.listiningChild()

            this.valuePercent = this.dataAsignature.percent
            this.valueIhs = this.dataAsignature.ihs
            this.order = this.dataAsignature.order
        },


    }
</script>

<style scoped>

</style>