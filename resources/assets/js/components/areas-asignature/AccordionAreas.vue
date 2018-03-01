<template>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default" v-for="(area,index) in customAreas">
            <div class="panel-heading" role="tab" :id="'headingOne'+index">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" :href="'#'+index"
                       aria-expanded="false" :aria-controls="index">
                        <small>{{index+1}} - {{area.custom_name}}</small>
                    </a>
                </h4>
            </div>

            <div :id="index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    eliminar - editar - agregar asignaturas - detalles
                </div>
            </div>
        </div>

    </div>
</template>

<script>


    export default {
        name: "accordion-areas",

        data() {
            return {
                customAreas: null,
            }
        },

        created() {
            this.$bus.$on('reload-add', () => {
                axios.get('getCustomAreas').then(res => {
                    this.customAreas = res.data;
                    console.log(res.data);
                });
            });
            axios.get('getCustomAreas').then(res => {
                this.customAreas = res.data;
                console.log(res.data);
            });

        }
    }
</script>

<style scoped>

</style>