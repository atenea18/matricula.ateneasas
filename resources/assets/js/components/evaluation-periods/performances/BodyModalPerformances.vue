<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li role="presentation" :class="!isActive?'at-active':''">
                        <a href="#" @click="clickOptions">
                            <i class="fas fa-search"></i> Consultar
                        </a>
                    </li>
                    <li role="presentation" :class="isActive?'at-active':''">
                        <a href="#" @click="clickOptions">
                            <i class="fas fa-plus-circle"></i> Crear Nuevo
                        </a>
                    </li>
                </ul>
                <hr>
            </div>
        </div>
        <!-- Componentes para seleccionar filtros -->
        <row-selects/>
        <!-- -->

        <div class="row" v-show="!isActive">
            <div class="col-md-12">
                <hr>
                <!-- Lista los desempeños, de acuerdo al filtro -->
                <table-performances></table-performances>
            </div>
        </div>
        <div class="row">
        </div>
    </div>

</template>

<script>
    import {mapState} from 'vuex'
    import RowSelects from './RowSelects'
    import TablePerformances from './TablePerformances'

    export default {
        name: "body-modal-performances",
        components: {
            RowSelects, TablePerformances
        },
        data() {
            return {
                isActive: false,
            }
        },
        computed: {
            ...mapState([
                'grade',
                'grades',
                'asignature',
                'institutionOfTeacher'
            ]),
        },
        methods: {
            clickOptions() {
                this.isActive = !this.isActive
            },
        }
    }
</script>

<style>
    .nav-pills > li > a {
        border-radius: 0px !important;
    }

    .at-active {
        background: #eee
    }

</style>