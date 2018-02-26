<template>
    <div class="row">
        <div class="col-md-12">
            <div>
                <tabs>
                    <tab name="Areas">
                        <div class="row">
                            <div class="col-md-5">
                                <add-areas></add-areas>
                            </div>
                            <div class="col-md-7">
                                <h4> Listado de Áreas añadidas</h4>
                                <accordion-areas></accordion-areas>
                            </div>
                        </div>
                    </tab>
                    <tab name="Asignaturas">
                        Second tab content
                    </tab>
                    <tab name="Pensum">
                        Third tab content
                    </tab>
                </tabs>
            </div>
        </div>
    </div>
</template>

<script>
    import AddAreas from './AddAreas';
    import AccordionAreas from './AccordionAreas';

    export default {
        name: "manger",
        components: {AddAreas, AccordionAreas},
        data(){
            return {
                title:'Adicionar Áreas',
            }
        }
    }
</script>

<style scoped>

</style>