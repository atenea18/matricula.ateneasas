<template>
    <tr>
        <td>{{setting.index+1}}</td>
        <td style="width:330px"> {{fullName}}</td>
        <td> 2</td>
        <template v-for="parameter in parameters">
            <td v-for="note_parameter in parameter.notes_parameter">
                <input-evaluation
                        :ref="''+setting.enrollment.id + asignature.id + setting.periodid + parameter.id"
                        :setting="setting" :noteparameter="note_parameter"
                        :parameter="parameter"></input-evaluation>
            </td>
            <input-parameter :setting="setting" :parameter="parameter"></input-parameter>
        </template>
        <td>
            <input class="form-control" style="padding:2px 2px" type="text">
        </td>
    </tr>
</template>

<script>
    import {mapState, mapMutations, mapGetters} from 'vuex';
    import InputEvaluation from './InputEvaluation'
    import InputParameter from './InputParameter'


    export default {
        name: "row-evaluation",
        components: {InputEvaluation, InputParameter},
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

            this.parameters.forEach((parameter) => {
                let nameEvent = '' + this.setting.enrollment.id + this.asignature.id + this.setting.periodid + parameter.id
                this.$bus.$on('set-dirty-'+ nameEvent, (pthis) => {
                    let arraychilds = this.$refs[pthis]
                    this.$bus.$emit('set-refs-' + nameEvent, arraychilds)
                });
            })

        },
        computed: {
            ...mapState([
                'parameters',
                'asignature'
            ]),

            fullName() {
                return this.setting.enrollment.student_last_name + " " + this.setting.enrollment.student_name
            },
            parametersAll() {
                return this.$store.state.parameters
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