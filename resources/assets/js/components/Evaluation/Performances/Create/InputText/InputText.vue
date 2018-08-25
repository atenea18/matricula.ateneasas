<template>
    <div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ propsData.scale.name }}</label>
                <textarea v-model="scale_selected.text" class="form-control" rows="3" @keyup="typingText"></textarea>
            </div>
        </div>
        <div class="col-md-6" v-if="propsData.scale.name_recommendation">
            <div class="form-group">
                <label class="control-label">{{propsData.scale.name_recommendation}}</label>
                <textarea v-model="scale_selected.recommendation" class="form-control" rows="3"
                          @keyup="typingText"></textarea>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "input-text",
        props: ['props-data'],
        data() {
            return {
                scale_selected: {
                    id: this.propsData.scale.id,
                    text: "",
                    recommendation: "",
                    word_expressions: this.propsData.scale.words_expressions_name,
                    is_copy: false,
                    is_higher: false,
                },
                scale_higher: this.propsData.scale_higher
            }
        },
        created() {

            if (this.scale_selected.id != this.scale_higher.id) {
                this.$bus.$on("EventRepeatText@InputText", text => {
                    this.scale_selected.text = ""
                    this.scale_selected.text = (text.length ? this.scale_selected.word_expressions : "") + " " + text
                });
            } else {
                this.scale_selected.is_higher = true
            }


            this.$bus.$on("EventChangeCopy@CreatePerformances", value => {
                this.scale_selected.is_copy = value
            })

            this.$bus.$on("EventSavedPerformance@CreatePerformances", data => {
                this.scale_selected.text = ""
                this.scale_selected.recommendation = ""
            })

        },
        methods: {
            typingText() {
                this.scale_selected.text = this.scale_selected.text.toUpperCase()
                this.scale_selected.recommendation = this.scale_selected.recommendation.toUpperCase()

                if (this.scale_higher.id == this.scale_selected.id) {
                    if (!this.scale_selected.is_copy) {
                        let text = this.scale_selected.text
                        this.$bus.$emit("EventRepeatText@InputText", text);
                    }
                }
            },
        },
        destroyed() {
            this.$bus.$off("EventRepeatText@InputText")
            this.$bus.$off("EventChangeCopy@CreatePerformances")
            this.$bus.$off("EventSavedPerformance@CreatePerformances")
        },
    }
</script>

<style scoped>

</style>