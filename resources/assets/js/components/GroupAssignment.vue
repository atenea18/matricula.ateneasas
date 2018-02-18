<template>
    <div>


        <div class="row" style="text-align: center; background-color:#eee; padding-top: 10px;">
            <div class="col-md-6">
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

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Seleccione un grupo</label>
                    <select v-on:change="getEnrollmentsByGrade" class="form-control" name="" id="" v-model="idGroup">
                        <option value="0">Seleccionar</option>
                        <option v-for="group in groups" :value="group.id">
                            {{ group.name}}
                            <small>{{group.headquarter_name}}</small>
                        </option>
                    </select>
                </div>
            </div>

        </div>

        <div class="row">
            <list-enrollments v-if="isRender" :enrollments="enrollments" :groups="groups" :title="titleOne"
                              :nameOption="option1" :typeQuery="'UPDATE'">
            </list-enrollments>
            <list-enrollments v-if="isRender" :enrollments="null" :groups="groups" :title="titleTwo"
                              :nameOption="option2" :typeQuery="'INSERT'">
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

            }
        },
        methods: {
            getEnrollmentsByGrade: function () {
                this.isRender = false;
                axios.get('enrollmentByGrade/' + this.idGroup).then(res => {
                    this.enrollments = res.data;
                    this.isRender = true;
                });
            },

            getGroupByGrade: function () {
                this.isRender = false;
                axios.get('groupsByGrade/' + this.idGrade).then(res => {
                    this.groups = res.data;
                });
            },


        },
        created() {
            this.$bus.$on('reload-enroll', () =>{
               this.getEnrollmentsByGrade();
            });
            axios.get('allgrades').then(res => {
                this.listGrade = res.data;
            });
        }

    }
</script>
