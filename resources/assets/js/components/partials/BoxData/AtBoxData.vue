<template>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td :colspan="tdata.length" style="text-align: center;">
                <strong>{{type.name.toUpperCase()}}</strong>
            </td>
        </tr>
        <tr>
            <td v-for="row in tdata" style="padding: 2px;">
                <at-box :ref="'box'+type.nameEv" :data="row"></at-box>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import AtBox from './AtBox';

    export default {
        name: "at-box-data",
        components: {
            AtBox
        },
        props: {
            type: {type: Object}
        },
        data() {
            return {
                tdata:[]
            }
        },
        methods: {
            getBy: function (id) {
                axios.get(this.type.url+'/' + id).then(res => {
                    this.tdata = res.data;
                });
            },
        },
        created() {
            this.$bus.$on('selected-id-'+this.type.tby, (id) => {
                this.getBy(id)
            });
        }
    }
</script>

<style scoped>

</style>