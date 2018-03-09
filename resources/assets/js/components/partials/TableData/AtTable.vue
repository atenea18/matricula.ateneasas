<template>
    <div>
        <vue-good-table v-if="state"
                        :title="type.name"
                        :columns="columns"
                        :rows="tdata"
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
                    <at-content-asignatures
                            :type="{nameEv:'asignatures-group-pensum-table', id:id, url:type.url}"
                            :idArea="props.row.areas_id"></at-content-asignatures>
                </td>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
    import AtContentAsignatures from './AtContentAsignatures';

    export default {
        name: "at-table",
        props: {
            data: {type: Array},
            type: {type: Object}
        },
        components: {
            AtContentAsignatures
        },
        data(){
            return{
                columns: [
                    {
                        label: '',
                        field: 'name_area',
                        filterable: true,
                    }
                ],
                tdata: [],
                dataGrades: [],
                getData: [],
                idSelectedGrade: 0,
                id: this.type.id || 0,
                picked: false,
                errors: [],
                state:false
            }
        },
        methods: {
            getBy: function (id) {

                axios.get('get'+this.type.url+'/'+id).then(res => {
                    this.tdata = res.data;
                    this.state = true
                });
            },
            deleteAreaPensum: function (area_id) {

                let url = 'deleteArea'+this.type.url;
                let data = {
                    group_id: this.id,
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
                        _this.state = false
                        _this.getBy(_this.id)
                    }
                });
            },


        },
        created() {

            this.$bus.$on('selected-id-' + this.type.tby, (id) => {
                this.state = false
                this.id = id
                console.log('-->selected-id-' + this.type.tby)
                this.getBy(id)
            });


        }
    }
</script>

<style scoped>

</style>