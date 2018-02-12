<template>
    <div>


        <div class="row" style="text-align: center">
            <div class="col-md-3">
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

            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Seleccione operación</label>
                    <div class="radio">
                        <label style="margin-right: 20px">
                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                            Incluir
                        </label>
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                            Excluir
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">

            </div>
        </div>


        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Seleccione un grupo</label>
                    <select v-on:change="" class="form-control" name="" id="" v-model="idGroupSelect">
                        <option value="0">Seleccionar</option>
                        <option v-for="group in groups" :value="group.id">
                            {{ group.name}}
                            <small>{{group.headquarter_name}}</small>
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <label for="">.</label>
                <div class="form-group">

                    <button class="btn btn-default"> Asignar</button>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Seleccione un grupo</label>
                    <select v-on:change="" class="form-control" name="" id="" v-model="idGroupSelectOther">
                        <option value="0">Seleccionar</option>
                        <option v-for="group in groups" :value="group.id">
                            {{ group.name}}
                            <small>{{group.headquarter_name}}</small>
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <label for="">.</label>
                <div class="form-group">

                    <button class="btn btn-default"> Asignar</button>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <h5>Estudiantes en Grupos</h5>
                <label>
                    <input type="checkbox">
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
                        <td>
                            <label>
                                <input type="checkbox"  v-on:click="checkMe(enrollment)" v-model="elsCheck[index]"
                                       :value="false">
                                {{ enrollment.student_last_name +" "+ enrollment.student_name}}
                            </label>

                        </td>
                        <td></td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Estudiantes sin Grupos</h5>
                <label>
                    <input type="checkbox" v-on:click="checkMeAll">
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
                        <td>
                            <label>
                                <input type="checkbox" class="checkOther" v-on:click="checkMe(enrollment)" v-model="elsCheckOther[index]"
                                       :value="false">
                                {{ enrollment.student_last_name +" "+ enrollment.student_name}}
                            </label>

                        </td>
                        <td></td>
                    </tr>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                idGrade: 0,
                idGroup: 0,
                listGrade: null,
                enrollments: null,
                groups: null,
                elsCheck: [],
                elsCheckOther: [],
                idGroupSelect: 0,
                idGroupSelectOther: 0
            }
        },
        methods: {
            getEnrollmentsByGrade: function () {
                this.elsCheck.forEach(value => {
                    value = false;
                });
                axios.get('enrollmentByGrade/' + this.idGroup).then(res => {
                    this.enrollments = res.data;
                    this.elsCheck = [];
                });
            },

            getGroupByGrade: function () {
                this.elsCheckOther.forEach(value => {
                    value = false;
                });
                axios.get('groupsByGrade/' + this.idGrade).then(res => {
                    this.groups = res.data;
                });
            },

            checkMe: function (enrollment) {
                console.log(enrollment.student_name);
            },

            checkMeAll: function (e) {
                let checkOther = document.querySelectorAll('.checkOther');
                checkOther.forEach((check, index) => {
                    check.checked = e.srcElement.checked;
                    this.elsCheckOther[index] = e.srcElement.checked;
                })
            }

        },
        mounted() {
            axios.get('all-grades').then(res => {
                this.listGrade = res.data;
            })
            ;
        }
    }
</script>
