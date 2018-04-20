<template>
    <div class="form-group padding-at">
        <label>Seleccionar Subgrupo</label>
        <select v-on:change="selected" class="form-control" v-if="objectSetting.isCreated"
                v-model="objectToSelect.gradeSelected">
            <option :value="{}">Seleccionar</option>
            <option v-for="row in grades" :value="row">
                {{ row.name }}
            </option>
        </select>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "select-subgroup",
        props: {},
        data() {
            return {
                objectSetting: {
                    isCreated: false,
                },
                objectToSelect: {
                    grades: [],
                    gradeSelected: {}
                }
            }
        },
        created() {

            this.objectToSelect.grades = this.$store.state.grades

            this.managerEvents()

            // Objetos inicializados
            this.objectSetting.isCreated = true
        },
        mounted() {

        },
        updated() {

        },
        computed: {
            ...mapState([
                'grades',
            ]),

        },
        methods: {
            managerEvents() {
                this.$bus.$on("to-receive-grade-selected", objectGrade => {
                    this.getSubgroupsByGrade(objectGrade)
                })
            },
            selected() {
                console.log(this.objectToSelect.gradeSelected)
            },
            getSubgroupsByGrade(objectGrade){

                let params = {
                    grade_id: objectGrade.id
                }

                axios.get('/ajax/getSubgroupsByGrade',{params}).then(res => {
                    console.log(res.data);
                })
            }

        }
    }
</script>

<style scoped>

</style>