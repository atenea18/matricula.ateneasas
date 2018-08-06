<template>
    <div>
        <div for="" id="title" style="font-size: 10px; height: 47px; text-align: center;">
            {{nameSubgroup}}
        </div>
        <div class="form-group">
            <div for="" style="font-size: 12px; text-align: center">IHS</div>
            <select v-on:change="" class="form-control" v-model="valueIhs">
                <option value="-1" selected></option>
                <option value="0">0</option>
                <option v-for="n in 12" :value="n">
                    {{ n }}
                </option>
            </select>
            <hr style="margin: 6px;">
            <div for="" style="font-size: 12px; text-align: center">%</div>
            <select class="form-control" v-model="valuePercent">
                <option value="-1" selected></option>
                <option value="0">0</option>
                <option v-for="n in 100" :value="n">
                    {{ n }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        name: "select-box",
        props: {
            objectInput: {type: Object},
        },
        data() {
            return {
                valueIhs: -1,
                valuePercent: -1,
                id: this.objectInput.id,
            }
        },
        computed:{
            nameSubgroup(){
                return this.objectInput.name
            }
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