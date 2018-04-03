<template>
    <div>
        <div for="" id="title" style="">
            {{grade.name.slice(0, 2)}}
        </div>
        <div class="form-group">
            <div for="" style="font-size: 12px; text-align: center">IHS</div>
            <select v-on:change="" class="form-control" v-model="valueIhs">
                <option value="-1" selected></option>
                <option value="0">0</option>
                <option v-for="n in 12">
                    {{ n }}
                </option>
            </select>
            <hr style="margin: 6px;">
            <div for="" style="font-size: 12px; text-align: center">%</div>
            <select v-on:change="" class="form-control" v-model="valuePercent">
                <option value="-1" selected></option>
                <option value="0">0</option>
                <option v-for="n in 100">
                    {{ n }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        name: "box-grade",
        props: {
            grade: {type: Object}
        },
        data() {
            return {
                valueIhs: -1,
                valuePercent: -1,
                idGrade:this.grade.id,
            }
        },
        methods: {},
        created() {

        }
    }
</script>

<style scoped>

    #title {
        margin-bottom: 6px;
        font-size: 12px;
        text-align: center;
        background-color: #1d75b3;
        padding: 4px;
        color: #FFFFFF;
    }

</style>