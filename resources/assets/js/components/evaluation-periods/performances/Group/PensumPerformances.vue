<template>
    <div>
        <template v-if="mainComponentObject.status">
            How are you doing here.?
        </template>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "pensum-performances",
        data() {
            return {
                mainComponentObject: {
                    config_type_id: 1,
                    config_type_description: "define el tipo de relación de desempeño, si es por columna o por filas ",

                    config_options_id: 1,
                    config_options_name: "row",

                    config_name: "relation_performances",
                    status: false,
                }
            }
        },
        created() {

        },
        computed: {
            ...mapState([
                'grade',
                'parameters',
                'periodSelected',
                'institutionOfTeacher',
                'isConexion',
                'groupPensum',
                'isTypeGroup',
                'configInstitution',
                'periodObjectSelected',
            ]),

        },
        mounted() {
            let config = this.$store.state.configInstitution.find(element => {
                if (element.config_type_id == this.mainComponentObject.config_type_id)
                    if (element.config_options_id == this.mainComponentObject.config_options_id)
                        return true

            }) || 0

            if (config) {
                this.mainComponentObject.status = true
                console.log(config.config_institution_name)
            }

            this.$store.state.parameters.forEach(element => {

                this.$bus.$on("parameter" + element.id, performance => {
                    console.log(performance)
                })
            })


        }

    }
</script>

<style scoped>

</style>