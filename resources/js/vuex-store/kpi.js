import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const kpi = {
    namespaced: true,
    state() {
        return {
            loading: true,
            errorMsg: '',
            report: '',
            reportDate: ''
        };
    },
    mutations: {
        loading(state, flag) {
            state.loading = flag;
        },
        errorMsg(state, msg) {
            state.errorMsg = msg;
        },
        report(state, report) {
            state.report = report;
        },
        reportDate(state, date) {
            state.reportDate = date;
        }
    },
    getters: {
        getLoading(state, getters) {
            return state.loading;
        }
    },
    actions: {
        processKPIData(context, report) {
            context.commit('report', report);
            context.commit('errorMsg', '');
            context.commit('loading',false);

            for(let elem in report) {
                if('date' in report[elem]) {
                    context.commit('reportDate', report[elem].date);
                    break;
                }
            }

        },
        getKPIReport(context, clientId) {
            context.commit('loading',true);

            let url = `/reports/${clientId}/kpi`;

            if(context.state.reportDate !== '') {
                url = url + `?date=${context.state.reportDate}`;
            }

            axios.get(url)
                .then(res => {
                    console.log('KPI report response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if ('success' in data) {
                            if (data['success']) {
                                context.commit('errorMsg', '');
                                context.dispatch('processKPIData', data['report']);
                                context.commit('loading',false);
                            } else {
                                context.commit('errorMsg', data['reason']);
                                context.commit('loading',false);
                            }
                        } else {
                            // unknown response
                            context.commit('errorMsg','Unknown Response from Anchor. Please Try Again.');
                            context.commit('loading',false);
                        }
                    }
                })
                .catch(e => {
                    console.log(e);
                    context.commit('errorMsg','Error - Unknown Response from Anchor. Please Try Again.');
                    context.commit('loading',false);
                });
        }
    }
};

export default kpi;
