<template>

    <div class="col-md-12">
        <h5 style="font-weight: bold; text-align: center">{{title}}</h5>
        <assignment :groups="groups" :nameOption="nameOption" :checksFalse="listCheckFalse" :checksTrue="listCheckTrue"
                    :typeQuery="typeQuery">
        </assignment>
        <div class="col-md-12">
            <label>
                <input type="checkbox" v-on:click="checkMeAll" v-model="isCheckMeAll">
                Seleccionar todos
            </label>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombres y Apellidos</th>
                    <th scope="col">Novedades</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(enrollment,index) in enrollments">
                    <td>
                        {{index+1}}
                    </td>
                    <item-enrollments :enrollment="enrollment" :index="index" v-on:click="getComponent">
                    </item-enrollments>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import ItemEnrollments from './ItemEnrollments';
    import Assignment from './Assignment';

    export default {
        name: "list-enrollments",
        components: {ItemEnrollments, Assignment},
        props: {
            enrollments: {type: Array},
            groups: {type: Array},
            title: {type: String},
            nameOption: {type: String},
            typeQuery: {type: String}
        },
        data() {
            return {
                isCheckMeAll: false,
                listCheckTrue: [],
                listCheckFalse: []

            }
        },
        methods: {

            checkMeAll: function () {
                this.$children.forEach((component) => {
                    component.isChecked = !this.isCheckMeAll
                    let i = component.index;
                    if (component.isChecked) {
                        this.listCheckFalse[i] = null;
                        this.listCheckTrue[i] = component._props.enrollment;
                    } else {
                        this.listCheckTrue[i] = null;
                        this.listCheckFalse[i] = component._props.enrollment;
                    }
                });
            },
            getComponent: function (component) {

                let i = component.index;
                let checked = !component._data.isChecked;
                if (checked) {
                    this.listCheckFalse[i] = null;
                    this.listCheckTrue[i] = component._props.enrollment;
                } else {
                    this.listCheckTrue[i] = null;
                    this.listCheckFalse[i] = component._props.enrollment;
                }
            }

        },
        mounted() {
            this.$children.forEach((component) => {
                let i = component.index;
                if (component.isChecked) {
                    this.listCheckFalse[i] = null;
                    this.listCheckTrue[i] = component._props.enrollment;
                } else {
                    this.listCheckTrue[i] = null;
                    this.listCheckFalse[i] = component._props.enrollment;
                }
            })
        }
    }
</script>

<style scoped>
    th {
        font-size: 13px;
        padding: 4px !important;
    }

    td {
        font-size: 12px;
        padding: 4px !important;
    }
</style>