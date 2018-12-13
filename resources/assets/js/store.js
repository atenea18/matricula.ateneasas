import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {

        stateEvaluation: {
            rol:'',
            parameters_selected: [],
            parameter_selected_id:0,
            grade_selected: {
                id:0,
                info:null
            },
            group_selected: {
                id:0,
                info:null
            },
            period_selected: {
                id:0,
                info:null
            },
            area_selected: {
                id:0,
                info:null
            },
            asignature_selected: {
                id:0,
                info:null
            },
            collectionNotes:[],
            displacement:{
                counter_input:1,
                total_notes_parameters:0,
                total_inputs:0,
                more_one:1,
                num_rows_enrollment:0,
                counter_input_over:1,
            },
            disabled: false

        },
        configInstitution: [],
        stateScale:{
            scales:[],
            max_scale: 0,
            min_scale: 1000,
        },
        stateInformation:{
            vector_periods: [],
            is_conexion: true,
            date_current: null,
        },
        counter: 0,
        totalInput: 0,
        counterInput: 1,
        periodSelected: 0,
        counterParameter: 0,
        institutionOfTeacher: 0,
        currentView: "",
        isConexion: true,
        isCollection: false,
        areas: [],
        grades: [],
        teachers: [],
        parameters: [],
        asignatures: [],
        subjectsType: [],
        collectionNotes: [],
        grade: Object,
        asignature: Object,
        groupPensum: Object,
        periodsworkingday: Object,
        isTypeGroup: true,
        scaleEvaluation: [],
        periodObjectSelected: null,
        dateNow: null,
        maxScale: 0,
        minScale: 200,


    },

    getters: {
        getDouble(state, getters) {
            return state.counter * 2
        },
    },
    mutations: {
        increment(state, payload = {}) {
            state.counter += payload.number || 1
        },
        decrement(state) {
            state.counter--
        },
        setConfigInstitution(state, payload) {
            state.configInstitution = payload.configInstitution || []
        },
        setInstitutionOfTeacher(state, payload) {
            state.institutionOfTeacher = payload.institutionOfTeacher || []
        },
        setScaleEvaluation(state, payload) {
            state.scaleEvaluation = payload.scaleEvaluation || []
        },
        setScales(state, payload) {
            payload.scales.forEach(element => {
                if (state.stateScale.max_scale < element.rank_end) {
                    state.stateScale.max_scale = element.rank_end
                }
                if (state.stateScale.min_scale > element.rank_start) {
                    state.stateScale.min_scale = element.rank_start
                }
            })
            state.stateScale.scales = payload.scales || []
        },
        setGrades(state, payload) {
            state.grades = payload.grades || []
        },
        setAreas(state, payload) {
            state.areas = payload.areas || []
        },
        setAsignatures(state, payload) {
            state.asignatures = payload.asignatures || []
        },
        setAsignatureById(state, payload) {
            state.asignature = payload.asignature || []
        },
        setGroupPensum(state, payload) {
            state.groupPensum = payload.group_pensum[0] || {}
        },
        setGradeById(state, payload) {
            state.grade = payload.grade || []
        },
        setSubjectsType(state, payload) {
            state.subjectsType = payload.subjectsType || []
        },
        setTeachers(state, payload) {
            state.teachers = payload.teachers || []
        },
        setParameters(state, payload) {
            let parameters = []


            if (state.asignature.subjects_type_id == 3 || state.asignature.grade_id == 4) {
                payload.group_type = "basic"
            }

            if (payload.parameters.length > 0) {
                parameters = payload.parameters.filter(element => {
                    return element.group_type == payload.group_type
                })
            }
            state.parameters = parameters || []
        },

        setParametersX(state, payload) {
            let parameters = []


            if (payload.asignature.subjects_type_id == 3 ||
                payload.asignature.grade_id == 4) {
                payload.group_type = "basic"
            }

            if (payload.parameters.length > 0) {
                parameters = payload.parameters.filter(element => {
                    return element.group_type == payload.group_type
                })
            }
            state.stateEvaluation.parameters_selected = parameters || []
        },

        setPeriodsWD(state, payload) {
            state.periodsworkingday = payload.periodsWD || []
        },
        setCollectionNotes(state, payload) {
            state.collectionNotes = payload.collectionNotes || []
        }


    },
    actions: {

        incrementAsync(context, payload) {
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    context.commit('increment', payload)
                    resolve()
                }, 2000)
            })
        },

        configInstitution(context, payload = {}) {
            let _this = this
            axios.get('/ajax/getConfigInstitution').then(res => {
                payload.configInstitution = res.data;
                context.commit('setConfigInstitution', payload)
            })
        },
        institutionOfTeacher(context, payload = {}) {
            let _this = this
            axios.get('/ajax/getInstitutionOfTeacher').then(res => {
                payload.institutionOfTeacher = res.data;
                context.commit('setInstitutionOfTeacher', payload)
                _this.dispatch('scaleEvaluation', payload)
            })
        },
        scaleEvaluation(context, payload = {}) {

            let params = {
                institution_id: payload.institutionOfTeacher.id
            }

            let url = "/ajax/getScaleEvaluation"

            axios.get(url, {params}).then(res => {

                payload.scaleEvaluation = res.data;
                context.commit('setScaleEvaluation', payload)
            })

        },
        scaleValoration(context, payload = {}) {

            let url = "/ajax/getScaleEvaluation"

            axios.get(url, ).then(res => {

                payload.scales = res.data;
                context.commit('setScales', payload)
            })

        },
        grades(context, payload = {}) {
            axios.get('/ajax/allgrades').then(res => {
                payload.grades = res.data;
                context.commit('setGrades', payload)
            })
        },
        areas(context, payload = {}) {
            axios.get('getAreas').then(res => {
                payload.areas = res.data;
                context.commit('setAreas', payload)
            })
        },
        asignatures(context, payload = {}) {
            axios.get('getAsignatures').then(res => {
                payload.asignatures = res.data;
                context.commit('setAsignatures', payload)
            })
        },
        asignatureById(context, payload = {}) {
            let params = payload
            axios.get('/teacher/evaluation/getAsignatureById', {params}).then(res => {
                payload.asignature = res.data;
                context.commit('setAsignatureById', payload)
            })
        },
        groupPensum(context, payload = {}) {
            let params = payload

            axios.get('/teacher/evaluation/getGroupPensum', {params}).then(res => {
                payload.group_pensum = res.data;
                context.commit('setGroupPensum', payload)
            })
        },
        gradeById(context, payload = {}) {
            axios.get('/teacher/evaluation/getGradeById/' + payload.grade_id).then(res => {
                payload.grade = res.data;
                context.commit('setGradeById', payload)
            })
        },
        subjectsType(context, payload = {}) {
            axios.get('getSubjectsType').then(res => {
                payload.subjectsType = res.data;
                context.commit('setSubjectsType', payload)
            });
        },
        teachers(context, payload = {}) {
            axios.get('getTeachers').then(res => {
                payload.teachers = res.data;
                context.commit('setTeachers', payload)
            });
        },

        parameters(context, payload = {}) {
            axios.get('/teacher/evaluation/evaluationParameter').then(res => {
                payload.parameters = res.data;
                context.commit('setParameters', payload)

            });
        },

        parametersX(context, payload = {}) {
            axios.get('/ajax/evaluation-parameter').then(res => {
                payload.parameters = res.data;
                context.commit('setParametersX', payload)

            });
        },


        periodsByWorkingDay(context, payload = {}) {
            let params = payload
            axios.get('/teacher/evaluation/getPeriodsByWorkingDay', {params}).then(res => {
                payload.periodsWD = res.data;
                context.commit('setPeriodsWD', payload)

            });
        },
        periodsByWorkingDayIns(context, payload = {}) {
            let params = payload
            axios.get('/ajax/getPeriodsByWorkingDay/' + params.workingdayid,).then(res => {
                payload.periodsWD = res.data;
                context.commit('setPeriodsWD', payload)

            });
        },
        collectionNotes(context, payload = {}) {
            let _this = this
            let params = payload
            if (payload.periodid != 0) {

                axios.get(
                    '/teacher/evaluation/getCollectionsNotes', {params}).then(res => {
                    if (typeof res.data == 'object') {
                        _this.state.isCollection = true
                        payload.collectionNotes = res.data
                        //console.log(res.data)
                        context.commit('setCollectionNotes', payload)
                        //console.log('I am an object')
                    }
                });
            }
        },

        verifyConexion(context, payload) {
            if (navigator.onLine) {
                this.state.isConexion = true
            } else {
                this.state.isConexion = false
            }
            //console.log(window.navigator)
        },
        verifyConexionX(context, payload) {
            if (navigator.onLine) {
                this.state.stateInformation.is_conexion = true
            } else {
                this.state.stateInformation.is_conexion = false
            }
        },

    }
})

export default store