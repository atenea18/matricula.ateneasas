<template>
    <div>
        <at-select :type="{name:type.name, nameEv:type.nameEv, tby:type.tby}" :data="tdata"></at-select>
    </div>
</template>

<script>
    import AtSelect from '../partials/AtSelect';

    export default {
        name: "at-select-by",
        components: {AtSelect},
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