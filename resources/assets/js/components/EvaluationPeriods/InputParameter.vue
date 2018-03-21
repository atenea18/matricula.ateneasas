<template>
    <td>
        <label for="">{{parameter.id}} </label>
    </td>
</template>

<script>
    export default {
        name: "input-parameter",
        props: {
            setting: {type: Object},
            parameter: {type: Object}
        },
        created() {
            let nameEvent =''+this.setting.enrollment.id + this.setting.asignatureid + this.setting.periodid + this.parameter.id
            this.$bus.$on('set-refs-' + nameEvent, (childs) => {
                console.log(childs)
                console.log("My")

            });

        },
    }
</script>

<style scoped>

</style>