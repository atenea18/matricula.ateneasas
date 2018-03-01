<template>
    <div>
        <small>{{title}}</small>
        <ul>
            <li v-for="data in customAreasOrAsignatures ">
                <a href="#" @click="deleteCustom(data.id)"><i class="fas fa-trash"></i></a>
                <small>{{data.name}}</small>

            </li>
            <li v-if="typeAreaOrAsignatures == 'asignatures' && customAreasOrAsignatures == ''">
                <a href="#"><i class="fas fa-plus"></i></a>
            </li>
            <li v-if="typeAreaOrAsignatures == 'areas'">
                <a href="#"><i class="fas fa-plus"></i></a>
            </li>
        </ul>

    </div>
</template>

<script>
    export default {
        name: "box",
        props: {
            typeAreaOrAsignatures: {type: String},
            idAreaOrAsignatures: {type: Number}
        },
        data() {
            return {
                customAreasOrAsignatures: [],
                arrayAreasAndAsignature: []
            }
        },
        methods: {
            deleteCustom: function (id) {
                if (this.typeAreaOrAsignatures == "areas") {
                    this.arrayAreasAndAsignature[0] = {
                        custom_asignatures_id: id,
                        custom_areas_id: this.idAreaOrAsignatures
                    }
                } else {
                    this.arrayAreasAndAsignature[0] = {
                        custom_asignatures_id: this.idAreaOrAsignatures,
                        custom_areas_id: id
                    }
                }
                this.sendDataDelete();
            },
            getDataForBox: function (method) {
                this.$bus.$on('reload-delete', () => {
                    axios.get(method).then(res => {
                        this.customAreasOrAsignatures = res.data;
                    });
                });
                this.$bus.$on('reload-add-table', () => {
                    axios.get(method).then(res => {
                        this.customAreasOrAsignatures = res.data;
                    });
                });
                axios.get(method).then(res => {
                    this.customAreasOrAsignatures = res.data;
                });
            },
            sendDataDelete: function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let _this = this;
                let data = this.arrayAreasAndAsignature;

                $.ajax({
                    type: "POST",
                    url: 'deleteAreasAndAsignatures',
                    data: {data},
                    success: function (response) {
                        _this.setting();
                    }
                });
            },
            setting: function () {

                if (this.typeAreaOrAsignatures == "areas") {
                    this.title = 'Asignaturas:';
                    this.getDataForBox('getAsignaturesByArea/' + this.idAreaOrAsignatures);
                } else {
                    this.title = 'Area:';
                    this.getDataForBox('getAreaByAsignature/'+this.idAreaOrAsignatures);
                }

            },
        },
        created() {
            this.setting();
        }
    }
</script>

<style scoped>

</style>