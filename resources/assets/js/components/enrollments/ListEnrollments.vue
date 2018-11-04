<template>

    <div class="col-md-12">
        <h5 style="text-align: center">
            <span style="font-weight: bold;">
            {{title}}
            </span>
            <div style="text-align: right; display:inline-block; right: 0px;">¿Desea registrar novedades?
                <input type="checkbox" class="checkOther" v-on:change="checkRegisterNews" v-model="isRegisterNews">
            </div>
        </h5>

        <assignment  v-show="!isRegisterNews" :groups="groups" :nameOption="nameOption" :checksFalse="listCheckFalse" :checksTrue="listCheckTrue"
                    :typeQuery="typeQuery"/>

        <div v-show="isRegisterNews">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Seleccione novedad</label>
                    <select class="form-control" v-model="registerNewEnrollments.news_selected">
                        <option :value="null">Seleccionar</option>
                        <option v-for="new_ in news" :value="new_">{{new_.name}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Fecha</label>
                    <input class="form-control" type="date" v-model="registerNewEnrollments.date">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="padding-top: 25px;">
                    <a class="btn btn-primary btn-block" @click="addNoveltyStudents"  >Guardar</a>
                </div>
            </div>
        </div>


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
            typeQuery: {type: String},
            news: {type: Array},
        },
        data() {
            return {
                isCheckMeAll: false,
                listCheckTrue: [],
                listCheckFalse: [],
                isRegisterNews: false,
                registerNewEnrollments: {
                    enrollments: [],
                    news_selected: null,
                    date: "",
                }
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
            },
            checkRegisterNews() {
                //this.$bus.$emit('checkRegisterNews@GroupAssignment', this.isRegisterNews)
            },

            addNoveltyStudents() {

                let enrollments = this.listCheckTrue.filter(Boolean)

                this.registerNewEnrollments.enrollments = enrollments

                let data = this.registerNewEnrollments

                let _this = this
                axios.post('/ajax/addNoveltyStudents', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            console.log("Yes, all is good.")
                            console.log(response)
                            //_this.$bus.$emit(`EventSaveNoAttendance:${_this.name_label_final}@LabelFinalNote`);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

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