<template>
    <div>
        <input @keyup="writingNotes" class="form-control" style="padding:2px 2px" type="text" v-model="valuenote"
        >
    </div>

</template>

<script>
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
        methods: {
            writingNotes() {
                let nameEvent = ''+this.setting.enrollment.id + this.setting.asignatureid + this.setting.periodid + this.parameter.id
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