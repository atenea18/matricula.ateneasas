<template>
    <div>
        <input @keyup="writingNotes" class="form-control" style="padding:2px 2px" type="text" v-model="valuenote"
        >
    </div>

</template>

<script>
    import {mapState, mapMutations, mapGetters} from 'vuex';

    export default {
        name: "input-evaluation",
        props: {
            setting: {type: Object},
            noteparameter: {type: Object},
            parameter: {type: Object}
        },
        data() {
            return {
                valuenote: "",
                evaluationperiodsid: 0
            }
        },
        created() {
            this.search(this.noteparameter.id)
        },
        computed: {
            ...mapState([
                'asignature',
                'periodSelected'
            ])

        },
        methods: {
            writingNotes() {
                let nameEvent = '' + this.setting.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected + this.parameter.id
                this.$bus.$emit('set-dirty-' + nameEvent, nameEvent)
                let nameEE = '' + this.setting.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected
                this.$bus.$off("set-store-note-" + nameEE);
                this.$bus.$on("set-store-note-" + nameEE, keyEvaluationPeriodId => {
                    this.evaluationperiodsid = keyEvaluationPeriodId
                    this.sendDataNotes()

                });

            },

            search(idnoteparameter) {
                let value = "";
                if (this.setting.enrollment.notes.length != 0) {
                    this.setting.enrollment.notes.forEach((note) => {
                        if (idnoteparameter == note.notes_parameters_id) {
                            value = note.value
                        }
                    })
                }
                this.valuenote = value


            },
            sendDataNotes() {
                //console.log(this.evaluationperiodsid)
                let data = {
                    value: this.valuenote,
                    overcoming: null,
                    evaluation_periods_id: this.evaluationperiodsid,
                    notes_parameters_id: this.noteparameter.id
                }
                axios.post('/teacher/evaluation/storeNotes', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            //console.log(response.data)
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }

        }
    }
</script>

<style scoped>

</style>