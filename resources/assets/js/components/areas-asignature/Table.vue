<template>

    <div>
        <div class="row">
            <div class="col-md-3">
                <label>Seleccionar Grado</label>
                <select v-on:change="getDataForTable" class="form-control" name="" v-model="idSelectedGrade" >
                    <option :value="0">Seleccionar</option>
                    <option v-for="grade in dataGrades" :value="grade.id">
                        {{ grade.name }}
                    </option>
                </select>
            </div>
        </div>
        <vue-good-table
                :title="title"
                :columns="columns"
                :rows="rows"
                :lineNumbers="true"
                :defaultSortBy="{field: 'custom_name', type: 'asc'}"
                :globalSearch="true"
                :paginate="true"
                class="table-custom"

                styleClass="table condensed table-bordered table-striped">
            <template slot="table-row" slot-scope="props">
                <td style="padding-left: 7px; padding-top: 10px; padding-bottom: 0px">
                    <strong class="title-area">
                        <i class="fas fa-angle-right"></i>
                        <a href="#" @click="deleteAreaPensum(props.row.areas_id)"><i class="fas fa-trash"></i></a> -
                        {{ props.row.name_area }}
                    </strong>
                    <box v-if="state" :idArea="props.row.areas_id" :idGrade="idSelectedGrade"></box>
                </td>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
    import Box from './Box';

    export default {
        name: "table-custom",
        components: {Box},
        data() {
            return {

                columns: [
                    {
                        label: '',
                        field: 'name_area',
                        filterable: true,
                    }


                ],
                title:"LISTADO DE PENSUM",
                rows: [],
                dataGrades: [],
                idSelectedGrade:0,
                getData:[],
                isVisibleTable:false,
                state:false
            }
        }, methods: {



            getDataForTable: function () {
                this.getData = [];
                this.state = false;
                axios.get('getPensumByGrade/'+this.idSelectedGrade).then(res => {
                    this.getData = res.data;
                    this.rows = this.getData;

                    this.state = true;
                });

            },
            deleteAreaPensum: function (area_id) {

                let url = 'deleteAreaPensumByGrade';
                let data = {
                    grade_id: this.idSelectedGrade,
                    areas_id: area_id
                }

                this.sendDataDelete(url, data);
            },

            sendDataDelete: function (url, data) {
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
                        _this.getDataForTable();
                    }
                });
            },

            getDataGrades(){
                axios.get('allgrades').then(res => {
                    this.dataGrades = res.data;
                });
            },
            setting: function () {
                this.getDataGrades();
            },

        },
        created() {
            this.$bus.$on('reload-grade', () => {
                this.getDataForTable();
            });
            this.setting();
        }
    }
</script>

<style>
    .title-area{
        font-size: 13px;
    }
    .table-custom h2,
    .table-custom th{
        font-size: 13px !important;
    }
</style>