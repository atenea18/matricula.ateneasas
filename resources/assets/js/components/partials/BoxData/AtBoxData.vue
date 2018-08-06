<template>
    <table class="table table-bordered" v-if="state">
        <tbody>
        <tr>
            <td :colspan="tdata.length" style="text-align: center;">
                <strong>{{type.name.toUpperCase()}}</strong>
            </td>
        </tr>
        <tr>
            <td v-for="(row,index) in tdata" style="padding: 2px;">
                <at-box ref="groupadd" :data="row" :index="index"></at-box>
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
                tdata: [],
                arrayDirtyBoxData: [],
                state: false
            }
        },
        methods: {
            getBy: function (id) {
                axios.get(this.type.url + '/' + id).then(res => {
                    this.tdata = res.data;
                    this.state = true
                });
            },
            setStorePensum() {


                this.arrayDirtyBoxData = [];
                this.$refs.groupadd.forEach((component) => {
                    if (component._data.valueIhs != -1 || component._data.valuePercent != -1) {
                        this.arrayDirtyBoxData.push(component._data);
                    }
                });

                this.$bus.$emit('set-send-at-box',this.arrayDirtyBoxData)

            },
        },
        created() {
            this.$bus.$on('set-send', (id) => {
                this.setStorePensum()
            })
            this.$bus.$on('selected-id-' + this.type.tby, (id) => {
                this.state = false
                this.getBy(id)
            });
        }
    }
</script>

<style scoped>

</style>