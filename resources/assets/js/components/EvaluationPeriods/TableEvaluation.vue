<template>
    <table class="table table-bordered">
        <thead>
        <tr style="font-size: 11px">
            <th scope="col">#</th>
            <th>
                Nombres Y Apellidos
            </th>
            <th>
                A
            </th>
            <template v-for="para in parameters">
                <th :colspan="para.notes_parameter.length+1">
                    {{para.parameter}}
                </th>
            </template>
            <th>
                Nota Final
            </th>
        </tr>
        </thead>
        <tbody>
        <template style="font-size: 11px" v-for="(enrollment, index) in collectionnotes">
            <row-evaluation
                    :setting="{index:index, periodid:periodid, enrollment:enrollment}"></row-evaluation>
        </template>

        </tbody>
    </table>
</template>

<script>
    import {mapState, mapMutations, mapGetters} from 'vuex'
    import RowEvaluation from './RowEvaluation'

    export default {
        name: "table-evaluation",
        components:{
            RowEvaluation
        },
        computed: {
            ...mapState([
                'parameters',
                'asignature'
            ]),
        }
    }
</script>

<style scoped>

</style>