<template>
    <tr>
        <td>{{setting.index}}</td>
        <td style="width:330px"> {{fullName}}</td>
        <td> 2</td>
        <template v-for="parameter in parameters">
            <td v-for="note_parameter in parameter.notes_parameter">

                <input-evaluation :setting="setting" :noteparameter="note_parameter"></input-evaluation>
            </td>
            <input-parameter></input-parameter>
        </template>
        <td>
            <input class="form-control" style="padding:2px 2px" type="text">
        </td>
    </tr>
</template>

<script>
    import {mapState, mapMutations, mapGetters} from 'vuex';
    import InputEvaluation from  './InputEvaluation'
    import InputParameter from './InputParameter'


    export default {
        name: "row-evaluation",
        components: { InputEvaluation, InputParameter},
        data() {
            return {
                enrollmentid: 0,
                isExistEvaluationPeriod: false,
                evaluationperiodid: 0,
                valuenote: "",
                state: false,

            }
        },
        created() {
            this.enrollmentid = this.setting.enrollment.id

        },
        computed: {
            ...mapState([
                'parameters'
            ]),

            fullName(){
                return this.setting.enrollment.student_last_name +" "+ this.setting.enrollment.student_name
            }
        },
        props: {
            setting: {type: Object},
        },
        methods: {
            writingNotes() {
                console.log("Hola K")
            },

        }

    }
</script>

<style scoped>

</style>