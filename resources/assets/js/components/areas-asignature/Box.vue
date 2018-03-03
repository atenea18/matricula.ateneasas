<template>
    <div>
        <p style="font-size: 11px;" v-if="errorPercent" v-bind:class="{ 'error-percent': errorPercent }">
            <strong>PORCENTAJES INCORRECTOS</strong>
        </p>
        <div style="font-size: 12px; text-align: center; background-color: #87bad0; color: #ffffff;
padding: 4px;">ASIGNATURAS
        </div>
        <table class="table table-bordered">
            <tbody>
            <template v-for="(item, index) in dataAsignaturesPensumByArea">
                <asignature :dataAsignature="item" :index="index">
                </asignature>
            </template>

            </tbody>
        </table>


    </div>
</template>

<script>
    import Asignature from './Asignature';

    export default {
        name: "box",
        props: {
            idArea: {type: Number},
            idGrade: {type: Number}
        },
        components: {Asignature},

        data() {
            return {
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

                axios.get('getAsignaturesPensumByGrade/' + this.idGrade + '/' + this.idArea).then(res => {
                    this.dataAsignaturesPensumByArea = res.data;
                    this.state = true;
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