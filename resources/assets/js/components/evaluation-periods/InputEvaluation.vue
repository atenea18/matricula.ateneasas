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
                let nameEvent = ''+this.setting.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected + this.parameter.id
                this.$bus.$emit('set-dirty-' + nameEvent, nameEvent)
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
            }

        }
    }
</script>

<style scoped>

</style>