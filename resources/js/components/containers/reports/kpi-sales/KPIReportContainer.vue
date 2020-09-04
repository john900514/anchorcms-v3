<template>
    <div class="kpi-report-container">
        <div class="inner-kpi-report">
            <kpi-sales v-if="!loading && (errorMsg === '')"
                       :report="report"
                       :report-date="reportDate"
                       :roi-mode="roiMode"
                       :roi-options="roiOptions"
            ></kpi-sales>
            <div class="loading errrr" v-if="!loading && (errorMsg !== '')">
                <div class="inner-loading">
                    <div class="spinny-loader">
                        <div class="center-wrapper">
                            <i class="fad fa-bug faa-ring animated faa-slow"></i>
                            <p>{{ errorMsg }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loading" v-if="loading">
                <div class="inner-loading">
                    <sexy-hurricane
                        :loading-msg="loadingMsg"
                    ></sexy-hurricane>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SexyHurricane from "../../../presenters/widgets/loading/SexyHurricane";
import KpiSales from "../../../presenters/reports/kpi-sales/KPISalesFullReportScreenComponent";
import { mapGetters, mapState, mapActions } from 'vuex';

export default {
    name: "KPIReportContainer",
    components: {
        KpiSales,
        SexyHurricane
    },
    props: ['clientId'],
    watch: {
        reportDate(date) {
            console.log(`report date changed to ${date}...reaching out to server for update...`);
            this.getKPIReport(this.clientId);
        }
    },
    data() {
        return {
            loadingMsg: 'Getting Latest Performance Reports...'
        };
    },
    computed: {
        ...mapState('kpi', ['loading', 'errorMsg', 'report', 'reportDate', 'roiMode', 'roiOptions']),
    },
    methods: {
        initAsideBar() {
            this.setContextTabActiveComponent('kpi-aside-context')
        },
        ...mapActions({
            setContextTabActiveComponent: 'asidebar/setContextTabActiveComponent',
            getKPIReport: 'kpi/getKPIReport'
        })
    },
    mounted() {
        this.initAsideBar();
        this.getKPIReport(this.clientId);
        console.log('KPIReportContainer mounted! '+ this.clientId)
    }
}
</script>

<style scoped>
@media screen {
    .kpi-report-container {
        width: 100%;
        height: 100%;
    }

    .inner-kpi-report {
        margin: 1%;
        display: flex;
        flex-flow: column;
        justify-content: center;
        align-items: center;
    }

    .errrr .spinny-loader {
        text-align: center;
    }

    .errrr .spinny-loader i {
        font-size: 3em;
    }

    .errrr .spinny-loader p {
        margin-top: 0.5em;
        font-weight: thin;
        font-size: 1.25em;
        text-transform: uppercase;
        letter-spacing: 0.1em
    }
}

@media screen and (max-width: 999px) {
    @media screen and (min-width: 500px) {
        .loading {
            margin-top: 25%;
        }
    }

    @media screen and (max-width: 499px) {
        .loading {
            margin-top: 35%;
        }
    }

}

@media screen and (min-width: 1000px) {
    .loading {
        margin-top: 20%;
    }
}
</style>
