<template>
    <div>
        <template style="text-align: center;">
            <div data-toggle="tooltip" v-show="isReprobate(objectInput.objectNote.value,objectInput.isReprobated)"
                 data-placement="top" :title="objectInput.asignature.name">
                <span style="text-align: center;" v-html="compareTo(objectInput.objectNote.value)"></span>
                <span style="text-align: center;" v-show="0<objectInput.objectNote.overcoming">/</span>
                <span style="text-align: center;" v-html="compareTo(objectInput.objectNote.overcoming)"></span>

            </div>
        </template>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "cell-notes",
        props: {
            objectInput: {type: Object},
        },
        data() {
            return {
                mainComponentObject: {
                    value: '',
                    overcoming: ''
                },
                classValue: '',
                classOvercoming: ''
            }
        },
        created() {
            this.compareTo()
        },
        computed: {
            ...mapState([
                'minScale'
            ]),
        },
        methods: {
            compareTo(valoration) {
                let value = '';
                if (valoration <= this.$store.state.minScale && valoration > 0) {
                    value = '<span style="color:red;">' + valoration + '</span>'
                } else {
                    value = valoration == 0 ? '' : valoration
                }
                return value
            },
            isReprobate(value, is_reprobated) {

                let visible = true
                if (is_reprobated) {
                    if (value <= this.$store.state.minScale && value > 0)
                        visible = true
                    else
                        visible = false;
                }

                return visible
            }

        }
    }
</script>

<style scoped>
    .red {
        color: red;
    }

</style>