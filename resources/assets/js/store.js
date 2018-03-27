import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        counter: 0,
        grades: [],
        areas: [],
        asignatures: [],
        subjectsType: [],
        teachers: [],
        parameters: [],
        asignature: Object,
        grade: Object,
        periodsworkingday: Object,
        periodSelected: 0,
        collectionNotes: [],
        isCollection: false,
        institutionOfTeacher: 0

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
            state.parameters = payload.parameters || []
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
            axios.get('/teacher/evaluation/getAsignatureById/' + payload.asignatureid +'/' + payload.grade_id).then(res => {
                payload.asignature = res.data;
                context.commit('setAsignatureById', payload)
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
            let t = this
            if (payload.periodid != 0) {

                axios.get(
                    '/teacher/evaluation/getCollectionsNotes/'
                    + payload.groupid + '/'
                    + payload.asignatureid + '/'
                    + payload.periodid
                ).then(res => {
                    if (typeof res.data == 'object') {
                        t.state.isCollection = true
                        payload.collectionNotes = res.data
                        //console.log(res.data)
                        context.commit('setCollectionNotes', payload)
                        //console.log('I am an object')
                    }
                });


            }

        }

    }
})

export default store