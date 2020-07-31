/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

const Vue = require('vue');
import Vuex from 'vuex';
window.Vuetify = require('vuetify');

Vue.use(Vuetify);
Vue.use(Vuex);

const opts = {
    icons: {
        iconfont: 'fa', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
    },
};

export default new Vuetify(opts);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('role-ability-assign', require('./components/containers/RoleAbilitySelectContainer.vue').default);
Vue.component('user-client-role-ability-assign', require('./components/containers/UserClientRoleAbilitySelectContainer.vue').default);
Vue.component('push-notifications', require('./components/containers/PushNotificationsContainer.vue').default);
Vue.component('main-dashboard', require('./components/containers/dashboards/DefaultDashboardContainer.vue').default);

Vue.component('kpi-report', require('./components/containers/reports/kpi-sales/KPISalesContainer.vue').default);

//Vue.component('checkbox-grid', require('./components/presenters/CheckboxGridComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VuexStore from './vuex-store/store';
window.store = VuexStore;

new Vue({
    el: '#app',
    store,
    data() {
        return {
            themeColor: ''
        };
    }
});
