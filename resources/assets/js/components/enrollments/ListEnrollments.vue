<template>

    <div class="col-md-6">
        <h5 style="font-weight: bold; text-align: center">{{title}}</h5>
        <assignment :groups="groups" :nameOption="nameOption" :checksFalse="listCheckFalse" :checksTrue="listCheckTrue">
        </assignment>
        <label>
            <input type="checkbox" v-on:click="checkMeAll" v-model="isCheckMeAll">
            Seleccionar todos
        </label>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombres y Apellidos</th>
                <th scope="col">Accion</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(enrollment,index) in enrollments">
                <th scope="row">
                    {{index+1}}
                </th>
                <item-enrollments :enrollment="enrollment" :index="index" v-on:click="setComponent"></item-enrollments>
                <td></td>
            </tr>
            </tbody>
        </table>


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
            nameOption: {type: String}
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
                })

                //console.log(this.listCheckTrue);
                //console.log(this.listCheckFalse);
            },
            setComponent: function (component) {
                let i = component.index;
                let checked = !component._data.isChecked;
                if (checked) {
                    this.listCheckFalse[i] = null;
                    this.listCheckTrue[i] = component._props.enrollment;
                } else {
                    this.listCheckTrue[i] = null;
                    this.listCheckFalse[i] = component._props.enrollment;
                }
                //console.log(this.listCheckTrue);
                //console.log(this.listCheckFalse);

            }


        }
    }
</script>

<style scoped>

</style>