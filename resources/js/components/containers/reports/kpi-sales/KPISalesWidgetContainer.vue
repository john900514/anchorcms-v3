<template>
    <kpi-report
        :loading="loading"
        :error="errorMsg"
        :report="report"
    ></kpi-report>
</template>

<script>
    import KpiReport from '../../../presenters/widgets/kpi-sales/KPIReport';
    import { mapState, mapActions } from 'vuex';
    export default {
        name: "KPISalesContainer",
        components: {
            KpiReport
        },
        props: ['clientId'],
        data() {
            return {
            };
        },
        computed: {
            ...mapState('kpi', ['loading', 'errorMsg', 'report']),
        },
        methods: {
            ...mapActions({
                getKPIReport: 'kpi/getKPIReport'
            })
        },
        mounted() {
            this.getKPIReport(this.clientId);

            console.log('KPISalesContainer mounted!');
        }
    }
</script>

<style scoped>
    h1 {
        text-align: center;
    }
</style>
