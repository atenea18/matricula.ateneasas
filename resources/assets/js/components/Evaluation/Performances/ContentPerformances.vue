<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li role="presentation" :class="!is_active?'at-active':''">
                        <a href="#" @click="clickLinkActions">
                            <i class="fas fa-search"></i> Consultar
                        </a>
                    </li>
                    <li role="presentation" :class="is_active?'at-active':''">
                        <a href="#" @click="clickLinkActions">
                            <i class="fas fa-plus-circle"></i> Crear Nuevo
                        </a>
                    </li>
                </ul>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
               <selects-performances></selects-performances>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
                <br>
               <table-performances></table-performances>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectsPerformances from '../GroupSelect/SelectsPerformances'
    import TablePerformances from "./Table/TablePerformances";
    export default {
        name: "content-performances",
        components: {
            TablePerformances,
            SelectsPerformances,
        },
        data() {
            return {
                is_active: false,
            }
        },
        created(){
            this.$bus.$on('eventElementsSelected@SelectsPerformances', data =>{
                console.log(data)
            })
        },
        methods: {
            clickLinkActions() {
                this.is_active = !this.is_active
            },
        }
    }
</script>

<style scoped>

</style>