import Vue from 'vue';
import Vuex from 'vuex';

import dashboard from './dashboard';
import kpi from './kpi';
import asidebar from "./asidebar/asidebar";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        asidebar,
        dashboard,
        kpi
    },
    state: {
        count: 2
    },
    mutations: {

    },
    getters: {

    },
    actions: {

    }
});
