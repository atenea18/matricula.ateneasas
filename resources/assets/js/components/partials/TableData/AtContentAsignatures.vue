<template>
    <div>
        <p style="font-size: 11px;" v-if="errorPercent" v-bind:class="{ 'error-percent': errorPercent }">
            <strong>PORCENTAJES INCORRECTOS</strong>
        </p>
        <div>ASIGNATURAS
        </div>
        <table class="table table-bordered">
            <tbody>
            <template  v-for="(item, index) in dataAsignaturesPensumByArea">

                <at-row-asignatures :type="{url:type.url, id:type.id}" :dataAsignature="item" :index="index">
                </at-row-asignatures>
            </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    import AtRowAsignatures from './AtRowAsignatures';

    export default {
        name: "at-content-asignatures",
        props: {
            idArea: {type: Number},
            type: {type: Object}
        },
        components: {AtRowAsignatures},

        data() {
            return {
                id: this.type.id || 0,
                dataAsignaturesPensumByArea: [],
                state: false,
                arrayDataToDelete: [],

            }
        },
        computed: {
            errorPercent: function () {
                let sum = 0;
                let count = 1;
                if (this.dataAsignaturesPensumByArea.length != 0) {
                    this.dataAsignaturesPensumByArea.forEach((asignature, index) => {
                        sum += asignature.percent;
                        count += asignature.percent != 0 ? index : 0;
                    })
                }
                if (sum == 100 && count == this.dataAsignaturesPensumByArea.length || sum == 0) {
                    return false;
                }
                return true;
            }
        },
        methods: {
            getDataForBox: function () {
                this.state = false
                axios.get('getAsignatures'+this.type.url+'/' + this.id + '/' + this.idArea).then(res => {
                    this.dataAsignaturesPensumByArea = res.data;
                    this.state = true
                });
            },
            setting: function () {
                this.getDataForBox();
            },
        },
        created() {
            this.$bus.$on('reload-asignatures', () => {
                this.getDataForBox();
            });


            this.setting();
        }
    }
</script>

<style scoped>
    .error-percent {
        color: rgb(255, 21, 16);
        margin: 2px !important;
    }
</style>