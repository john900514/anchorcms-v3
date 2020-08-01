<template>
    <kpi-report
        :loading="loading"
        :error="errorMsg"
        :report="report"
    ></kpi-report>
</template>

<script>
    import KpiReport from '../../../presenters/widgets/kpi-sales/KPIReport';
    export default {
        name: "KPISalesContainer",
        components: {
            KpiReport
        },
        props: ['clientId'],
        data() {
            return {
                loading: false,
                errorMsg: '',
                report: ''
            };
        },
        computed: {},
        methods: {
            processKPIData(data) {
                console.log('processing data...', data);

                for(let reportType in data) {
                    switch(reportType) {
                        case 'daily':
                            data[reportType]['headers'] = [];
                            for(let market in data[reportType]) {
                                for(let column in data[reportType][market]) {
                                    data[reportType]['headers'].push(column);
                                }
                                break;
                            }
                            break;

                        case 'monthly':
                            data[reportType]['headers'] = [];
                            for(let market in data[reportType]) {
                                for(let column in data[reportType][market]) {
                                    data[reportType]['headers'].push(column);
                                }
                                break;
                            }
                            break;

                        case 'promo':

                            break;

                        default:
                            console.log(`Unknown Report Type ${reportType}. Skipping...`);
                    }
                }

                this.report = data;
                this.errorMsg = '';
            },
            getKPIReport() {
                let _this = this;
                this.loading = true;

                axios.get(`reports/${this.clientId}/kpi`)
                    .then(res => {
                        console.log('KPI report response - ', res);

                        if('data' in res) {
                            let data = res.data;

                            if ('success' in data) {
                                if (data['success']) {
                                    //_this.errorMsg = 'Processing Data';
                                    _this.errorMsg = '';
                                    _this.processKPIData(data['report']);
                                    _this.loading = false;
                                } else {
                                    _this.errorMsg = data['reason'];
                                    _this.loading = false;
                                }
                            } else {
                                // unknown response
                                _this.errorMsg = 'Unknown Response from Anchor. Please Try Again.';
                                _this.loading = false;
                            }
                        }
                    })
                    .catch(e => {
                        console.log(e);
                        _this.errorMsg = 'Could not communicate with Anchor. Please Try Again.'
                        _this.loading = false;
                    })
            }
        },
        mounted() {
            this.getKPIReport();

            console.log('KPISalesContainer mounted!');
        }
    }
</script>

<style scoped>
    h1 {
        text-align: center;
    }
</style>
