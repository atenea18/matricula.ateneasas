<template>
    <div>
        <div class="row" style="text-align: center; background-color:#eee; padding-top: 10px;">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="">Seleccione un grado</label>
                    <select v-on:change="getGroupByGrade" class="form-control" name="" id="" v-model="idGrade">
                        <option value="0">Seleccionar</option>
                        <option v-for="grade in listGrade" :value="grade.id">
                            {{ grade.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group">
                    <label for="">Seleccione un grupo</label>
                    <select v-on:change="getEnrollmentsByGroup" class="form-control" name="" id="" v-model="idGroup">
                        <option value="0">Seleccionar</option>
                        <option v-for="group in groups" :value="group.id">
                            {{ group.name}}
                            <small>{{group.headquarter_name}}</small>
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group" style="padding-top: 25px;">
                    <a class="btn btn-default btn-block" style="font-size: 11px" @click="lookLists">{{lookEnrollment}}</a>
                </div>
            </div>

        </div>

        <div class="row">
            <list-enrollments v-show="!isLook" v-if="isRender" :enrollments="enrollments" :groups="groups" :title="titleOne"
                              :nameOption="option1" :typeQuery="'UPDATE'" :news="news" :group_id="idGroup">
            </list-enrollments>

            <list-enrollments v-show="isLook" v-if="isRender" :enrollments="enrollmentsWithOut" :groups="groups" :title="titleTwo"
                              :nameOption="option2" :typeQuery="'INSERT'" :news="news" :group_id="idGroup">
            </list-enrollments>

            <div class="col-md-12" v-else="isRender">
                <h5 class="alert-info"
                    style="padding: 10px; text-align: center; text-transform: uppercase; font-weight: bold">
                    Seleccione un grado y luego un grupo. </h5>
            </div>
        </div>
    </div>
</template>

<script>
    import ListEnrollments from './enrollments/ListEnrollments';

    export default {
        name: "group-assignment",
        components: {ListEnrollments},
        data() {
            return {
                idGrade: 0,
                idGroup: 0,
                titleOne: "ESTUDIANTES EN GRUPOS",
                titleTwo: "ESTUDIANTES SIN GRUPOS",
                option1: "option-with",
                option2: "option",
                groups: null,
                listGrade: null,
                isRender: false,
                enrollments: null,
                enrollmentsWithOut: null,
                lookEnrollment: "Ver estudiante SIN grupos",
                isLook:false,
                lookClass:"btn-primary",
                news: []
            }
        },
        methods: {
            getEnrollmentsByGroup: function () {
                this.isRender = false;
                axios.get('enrollmentByGroup/' + this.idGroup).then(res => {
                    this.enrollments = res.data;
                    this.isRender = true;
                });

                axios.get('enrollmentsWithOutGroup/'+ this.idGrade).then(res => {
                    this.enrollmentsWithOut = res.data;
                    this.isRender = true;
                });

                axios.get('/ajax/news').then(res => {
                    this.news = res.data;
                });


            },

            getGroupByGrade: function () {
                this.isRender = false;
                this.idGroup = 0;
                axios.get('groupsByGrade/' + this.idGrade).then(res => {
                    this.groups = res.data;
                });
            },
            lookLists: function () {
                this.isLook = !this.isLook;
                if(this.isLook){
                    this.lookClass = "btn-success";
                    this.lookEnrollment = "Ver estudiante EN grupos"
                }

                else{
                    this.lookClass = "btn-primary";
                    this.lookEnrollment = "Ver estudiante SIN grupos";
                }
            },

        },
        created() {
            this.$bus.$on('reload-enroll', (group_id) => {
                this.idGroup = group_id;
                this.getEnrollmentsByGroup();
            });
            axios.get('allgrades').then(res => {
                this.listGrade = res.data;
            });
        }

    }
</script>
