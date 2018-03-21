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
        setGrades(state, payload) {
            state.grades = payload.grades || []
        },
        setAreas(state, payload) {
            state.areas = payload.areas || []
        },
        setAsignatures(state, payload) {
            state.asignatures = payload.asignatures || []
        },
        setSubjectsType(state, payload){
            state.subjectsType = payload.subjectsType || []
        },
        setTeachers(state, payload){
            state.teachers = payload.teachers || []
        },
        setParameters(state, payload){
            state.parameters = payload.parameters || []
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
        grades(context, payload = {}) {
            axios.get('allgrades').then(res => {
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
        subjectsType(context, payload = {}){
            axios.get('getSubjectsType').then(res => {
                payload.subjectsType = res.data;
                context.commit('setSubjectsType', payload)
            });
        },
        teachers(context, payload = {}){
            axios.get('getTeachers').then(res => {
                payload.teachers = res.data;
                context.commit('setTeachers', payload)
            });
        },
        parameters(context, payload = {}){
            axios.get('/teacher/evaluation/evaluationParameter').then(res => {
                payload.parameters = res.data;
                context.commit('setParameters', payload)

            });
        }
    }
})

export default store