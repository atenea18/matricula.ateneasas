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

                                <!--<accordion-areas></accordion-areas>-->
                            </div>
                        </div>
                    </tab>
                    <tab name="Ver">
                        <div class="row">
                            <div class="col-md-12">
                                <table-custom></table-custom>
                            </div>
                        </div>
                    </tab>
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