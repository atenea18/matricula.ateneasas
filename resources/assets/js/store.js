import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {

        counter: 0,
        totalInput:0,
        counterInput:1,
        periodSelected: 0,
        counterParameter:0,
        institutionOfTeacher: 0,
        currentView:"",
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
        setInstitutionOfTeacher(state, payload) {
            state.institutionOfTeacher = payload.institutionOfTeacher || []
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

            if(payload.parameters.length > 0 ){
                parameters = payload.parameters.filter(element =>{
                    return element.group_type == payload.group_type
                })
            }

            console.log(parameters)
            state.parameters = parameters || []
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
        institutionOfTeacher(context, payload = {}) {
            axios.get('/ajax/getInstitutionOfTeacher').then(res => {
                payload.institutionOfTeacher = res.data;
                context.commit('setInstitutionOfTeacher', payload)
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
            axios.get('/teacher/evaluation/getAsignatureById/' + payload.asignatureid + '/' + payload.grade_id).then(res => {
                payload.asignature = res.data;
                context.commit('setAsignatureById', payload)
            })
        },
        groupPensum(context, payload = {}) {
            let params = {
                group_id: payload.group_id,
                asignatures_id: payload.asignatures_id,
                school_year_id: payload.school_year_id
            }
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

        periodsByWorkingDay(context, payload = {}) {
            axios.get('/teacher/evaluation/getPeriodsByWorkingDay/' + payload.workingdayid).then(res => {
                payload.periodsWD = res.data;
                context.commit('setPeriodsWD', payload)

            });
        },
        collectionNotes(context, payload = {}) {
            let _this = this
            if (payload.periodid != 0) {

                axios.get(
                    '/teacher/evaluation/getCollectionsNotes/'
                    + payload.groupid + '/'
                    + payload.asignatureid + '/'
                    + payload.periodid
                ).then(res => {
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

    }
})

export default store