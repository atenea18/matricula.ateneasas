<template>
    <div class="row">
        <div class="col-md-12">
            <div>
                <tabs>
                    <tab name="Pensum">
                        <div class="row">
                            <div class="col-md-12">
                                <add></add>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <!--<table-custom typeAreaOrAsignatures="areas" title="LISTADO DE ÁREAS"></table-custom>-->
                                <!--<accordion-areas></accordion-areas>-->
                            </div>
                        </div>
                    </tab>
                    <!--<tab name="Asignaturas">
                        <div class="row">
                            <div class="col-md-12">
                                <add title="ADICIONAR ASIGNATURAS" typeAreaOrAsignatures="asignatures"
                                     typeComponent="insert"></add>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <table-custom typeAreaOrAsignatures="asignatures"
                                              title="LISTADO DE ASIGNATURAS"></table-custom>
                            </div>
                        </div>
                    </tab>-->
                    <!--<tab name="Pensum">
                        Third tab content
                    </tab>-->
                </tabs>
            </div>
        </div>
    </div>
</template>

<script>
    import Add from './Add';
    import AccordionAreas from './AccordionAreas';
    import TableCustom from "./Table";

    export default {
        name: "manger",
        components: {
            TableCustom,
            Add, AccordionAreas
        },
        data() {
            return {
                title: 'Adicionar Áreas',
            }
        }
    }
</script>

<style scoped>

</style>