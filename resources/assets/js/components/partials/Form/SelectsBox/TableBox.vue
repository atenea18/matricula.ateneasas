<template>
    <table class="table table-bordered" v-if="state">
        <tbody>
        <tr>
            <td :colspan="objectToTableBox.subgroups.length" style="text-align: center;">
                <strong>{{objectInput.name.toUpperCase()}}</strong>
            </td>
        </tr>
        <tr>
            <td v-for="(row,index) in objectToTableBox.subgroups" style="padding: 2px;">
                <select-box ref="subgroupsbox" :objectInput="row" :index="index"></select-box>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import SelectBox from './SelectBox'

    export default {
        name: "table-box",
        components: {
            SelectBox
        },
        props: {
            objectInput: {type: Object}
        },
        data() {
            return {
                objectToTableBox: {
                    subgroups: []
                },
                arrayDirtyBoxData: [],
                state: false
            }
        },
        created() {
            this.managerEvents()
        },
        methods: {
            managerEvents() {
                this.$bus.$on('i-can-search-dirtybox', () => {
                    this.getDirtyBox()
                })

                this.$bus.$on("to-receive-grade-selected", objectGrade => {
                    this.getSubgroupsByGrade(objectGrade)
                })
            },
            getSubgroupsByGrade(objectGrade) {

                let params = {grade_id: objectGrade.id}
                axios.get('/ajax/getSubgroupsByGrade', {params}).then(res => {
                    this.objectToTableBox.subgroups = res.data;
                    this.state = true
                })
            },

            getDirtyBox() {

                this.arrayDirtyBoxData = [];
                this.$refs.subgroupsbox.forEach((component) => {
                    if (component._data.valueIhs != -1 || component._data.valuePercent != -1) {
                        this.arrayDirtyBoxData.push(component._data);
                    }
                });
                //console.log(this.arrayDirtyBoxData)
                this.$bus.$emit('i-can-to-receive-subgroups', this.arrayDirtyBoxData)

            },
        },

    }
</script>

<style scoped>

</style>