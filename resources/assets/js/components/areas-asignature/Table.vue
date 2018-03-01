<template>

    <div>

        <vue-good-table
                :title="title"
                :columns="columns"
                :rows="rows"
                :lineNumbers="true"
                :defaultSortBy="{field: 'custom_name', type: 'asc'}"
                :globalSearch="true"
                :paginate="true"

                styleClass="table condensed table-bordered table-striped">
            <template slot="table-row" slot-scope="props">
                <td>
                    {{ props.row.custom_name }}
                    <box :typeAreaOrAsignatures="typeAreaOrAsignatures" :idAreaOrAsignatures="props.row.id"></box>
                </td>
                <td>{{props.row.subjects_type_name}}</td>
                <td>
                    <a href="#" @click="deleteCustom(props.row.id)"><i class="fas fa-trash"></i></a>
                    <a href="#"><i class="fas fa-pen-square"></i></a>
                </td>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
    import Box from './box';

    export default {
        name: "table-custom",
        props: {
            title: {type: String},
            typeAreaOrAsignatures: {type: String},
        },
        components: {
            Box
        },
        data() {
            return {
                customAreasOrAsignatures: [],
                columns: [
                    {
                        label: 'Nombre',
                        field: 'custom_name',
                        filterable: true,
                    },
                    {
                        label: 'Tipo',
                        field: '',
                    }
                    ,
                    {
                        label: 'Acción',
                        field: '',
                    }
                ],
                rows: [],
            }
        }, methods: {

            setting: function () {

                // Areas or Asignatures
                if (this.typeAreaOrAsignatures == "areas") {
                    this.getDataForTable('getCustomAreas');
                } else {
                    this.getDataForTable('getCustomAsignatures');
                }
            },

            getDataForTable: function (method) {
                this.$bus.$on('reload-add', () => {
                    axios.get(method).then(res => {
                        this.customAreasOrAsignatures = res.data;
                        this.rows = this.customAreasOrAsignatures;
                    });

                });
                axios.get(method).then(res => {
                    this.customAreasOrAsignatures = res.data;
                    this.rows = this.customAreasOrAsignatures;
                });
                this.$bus.$emit('reload-add-table', null)
            },
            deleteCustom: function (id) {
                let data = {
                    id: id
                }

                if (this.typeAreaOrAsignatures == "areas") {
                    this.sendDataDelete('deleteCustomArea', data);
                } else {
                    this.sendDataDelete('deleteCustomAsignature',data);
                }


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
                        _this.$bus.$emit('reload-delete', null)
                        _this.setting();
                    }
                });
            },

        },
        created() {
            this.setting();
        }
    }
</script>

<style>
    * {
        font-size: 13px !important;
    }

</style>