<template>
    <div>
        <template style="text-align: center;">
            <div data-toggle="tooltip" v-show="isReprobate(objectInput.objectNote.value,objectInput.isReprobated)"
                 data-placement="top" :title="objectInput.asignature.name">
                <span style="text-align: center;" v-html="getValue(objectInput.objectNote.value, objectInput.objectNote.overcoming)"></span>

                <!--
                <span style="text-align: center;" v-html="compareTo(objectInput.objectNote.value)"></span>
                <span style="text-align: center;" v-show="0<objectInput.objectNote.overcoming">/</span>
                <span style="text-align: center;" v-html="compareTo(objectInput.objectNote.overcoming)"></span>
                -->

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
            },
            getValue(average, overcoming) {
                let value = "";
                let value_ = "";
                if (average <= this.$store.state.minScale && average > 0) {
                    value = '<span style="color:red;">' + average + '</span>'
                }else{
                    value = average?average:""
                }

                if (overcoming <= this.$store.state.minScale && overcoming > 0) {
                    value_ = '<span style="color:red;">' + overcoming + '</span>'
                }else{
                    value_ = overcoming?overcoming:""
                }

                if (overcoming) {
                    return '<span>' + value_ + " / " + value + '</span>'
                }
                else
                    return '<span>' + value + '</span>'
            }

        }
    }
</script>

<style scoped>
    .red {
        color: red;
    }

</style>