<template>

    <div class="col-md-12">
        <h5 style="text-align: center">
            <span style="font-weight: bold;">
            {{title}}
            </span>
            <h6 style="text-align: right; display:inline-block; right: 0px;"> - ¿DESEA REGISTRAR NOVEDADES?
                <input type="checkbox" class="checkOther" v-on:change="checkRegisterNews" v-model="isRegisterNews">
            </h6>
        </h5>

        <assignment v-show="!isRegisterNews" :groups="groups" :nameOption="nameOption" :checksFalse="listCheckFalse"
                    :checksTrue="listCheckTrue"
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
                    <a class="btn btn-primary btn-block" @click="addNoveltyStudents">Guardar</a>
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
                    <th scope="col">Novedad</th>
                    <th scope="col">Fecha Novedad</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(enrollment,index) in enrollments">
                    <td>
                        {{index+1}}
                    </td>
                    <item-enrollments :enrollment="enrollment" :index="index" v-on:click="getComponent">
                    </item-enrollments>
                    <td>
                        <div v-if="enrollment.enrollment_news_id">
                            <a href="#" alt="Eliminar" @click="deleteEnrollmentNews(enrollment.enrollment_news_id)">
                                <i class="fas fa-trash-alt"></i>
                            </a> - {{enrollment.news_name}}
                        </div>
                    </td>
                    <td>
                        {{enrollment.date}}
                    </td>
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
        props: ["enrollments", "groups", "title", "nameOption", "typeQuery", "news", "group_id"],
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
                axios.post('/ajax/NoveltyStudents/add', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            _this.$bus.$emit('reload-enroll', _this.group_id)
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            deleteEnrollmentNews(enrollment_news_id) {
                let _this = this
                let data = {enrollment_news_id: enrollment_news_id}
                axios.post('/ajax/NoveltyStudents/delete', {data})
                    .then(function (response) {
                        if (response.status == 200) {
                            _this.$bus.$emit('reload-enroll', _this.group_id)
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