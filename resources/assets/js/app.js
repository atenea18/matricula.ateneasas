
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.toastr = require('toastr')

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('group-assignment', require('./components/GroupAssignment.vue'));
Vue.component('manager', require('./components/areas-asignature/Manger.vue'));
Vue.component('evaluation-manager', require('./components/EvaluationManager.vue'));
Vue.component('subgroup-manager', require('./components/SubgroupManager.vue'));
Vue.component('statistics-manager', require('./components/StatisticsManager.vue'));


import VueSweetAlert from 'vue-sweetalert';
import EventBus from './plugin/event-bus';
import Tabs from 'vue-tabs-component';
import VueGoodTable from 'vue-good-table';
import store from './store'




Vue.use(VueSweetAlert);
Vue.use(VueGoodTable);
Vue.use(EventBus);
Vue.use(Tabs);

const app = new Vue({
    el: '#app',
    store
});

// App.js
$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});
$("#menu-toggle-2").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled-2");
    // $('#menu ul').hide();
});
