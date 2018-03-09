<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <at-select :type="{name:'Grado', nameEv:'grade-table', tby:'null', validate:true}"
                           :data="grades"></at-select>
            </div>
            <div class="col-md-6">
                <at-select-by
                        :type="{name:'un Grupo', nameEv:'group-table', url:'groupsByGrade', tby:'grade-table'}"></at-select-by>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <at-table :type="{name:'Pensum Grupo', id:0, nameEv:'group-pensum-table', url:'PensumByGroup', tby:'group-table'}"></at-table>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapMutations, mapGetters} from 'vuex';
    import AtSelect from '../AtSelect'
    import AtSelectBy from '../AtSelectBy'

    import AtTable from './AtTable'

    export default {
        name: "at-table-pensum",
        components: {
            AtSelect,
            AtSelectBy,
            AtTable
        },
        data() {
            return {
                assignmentGroup: {
                    idgrade: 0,
                    idgroup: 0,
                    idarea: 0,
                    asignatures: [],
                    idsubjectsType: 0,
                    idteacher: 0,
                    order: 0
                },
                state:false
            }
        },
        computed: {
            ...mapState(['grades', 'areas', 'asignatures', 'subjectsType']),
        },
        created() {
            this.listiningChild();

        },
        methods: {

            listiningChild() {
                this.$bus.$on('selected-id-grade-table', (id) => {

                });
                this.$bus.$on('selected-id-area-table', (id) => {
                    this.assignmentGroup.idarea = id
                })
                this.$bus.$on('selected-id-group-table', (id) => {

                    //this.assignmentGroup.idgroup = id
                    this.$bus.$emit('change-id-group-table', id)

                })
                this.$bus.$on('selected-id-subjectType-table', (id) => {
                    this.assignmentGroup.idsubjectsType = id
                })
                this.$bus.$on('selected-values-asignature-table', (values) => {
                    this.assignmentGroup.asignatures = values
                })
            },

            getDataForTable: function (id) {
                this.state = false;
                axios.get('getPensumByGrade/' + id).then(res => {
                    this.rows = res.data;
                });

            },
        },


    }
</script>

<style>
    .title-area {
        font-size: 13px;
    }

    .table-custom h2,
    .table-custom th {
        font-size: 13px !important;
    }
</style>