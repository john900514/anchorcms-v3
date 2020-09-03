<template>
    <div class="reporting-zone">
        <div class="inner-reporting-zone">
            <div class="kpi-piece">
                <div class="inner-kpi-piece" v-if="repData !== ''">
                    <div class="kpi-full-report">
                        <div class="inner-kpi-full-report" style="text-align: center">
                            <!-- Daily Sales -->
                            <div class="daily-sales-segment">
                                <h2 class="sales-title">--- DAILY Sales for {{ reportDate }} ---</h2>
                                <div class="inner-daily-sales row">
                                    <div class="report-table" v-for="(reportData, reportKey) in repData">
                                        <div class="card text-white bg-primary text-center">
                                            <div class="card-header"><h2 style="text-align: center;">{{ reportData.name }}</h2></div>
                                            <div class="card-body">
                                                <blockquote class="card-bodyquote">
                                                    <table style="margin:1em auto;">
                                                        <thead>
                                                        <tr><th v-for="(columnName, cidx) in  reportData.columns">{{columnName}}</th></tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(marketData, marketName) in  reportData.report">
                                                            <td v-for="(mval, mcol) in marketData">{{ typeof(mval) !== 'number' ? mval : transformNumber(mval) }}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="monthly-sales-segment">
                                <h2 class="sales-title">--- MONTHLY Sales for {{ reportDate }} ---</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "KPISalesFullReportScreenComponent",
    props: ['report', 'reportDate'],
    watch: {
        report(stuff) {
            console.log('Received a new report! ', stuff);
            this.repData = stuff;
        }
    },
    data() {
        return {
            repData: ''
        };
    },
    computed: {
        getDate() {
            return `${this.getMonth}-${this.getDay}-${this.getYear}`
        },
        getDay() {
            return new Date().getDate()
        },
        getMonth() {
            return parseInt(new Date().getMonth() + 1)
        },
        getYear() {
            return new Date().getFullYear()
        },
    },
    methods: {
        transformNumber(n)
        {
            let results = 0;
            // Is int.
            if(Number(n) === n && n % 1 === 0)
            {
                results = parseInt(n);
            }
            else
            {
                results = parseFloat(n).toFixed(2);
            }
            return results;
        }
    },
    mounted() {
        this.repData = this.report;
        console.log('KPISalesFullReportScreenComponent mounted!', this.repData);
    }

}
</script>

<style scoped>
    @media screen {
        .sales-title {
            text-align: center;
            padding-bottom: 2.5%;
            font-size: 2em;
        }

        .reporting-zone {
            width: 100%;
            height: 100%;
        }

        .inner-reporting-zone {
            display: flex;
            flex-flow: column;
        }

        .kpi-piece {
            width: 100%;
            height: 100%;
        }

        .inner-kpi-piece {
            display: flex;
            flex-flow: column;
        }

        .kpi-full-report {
            width: 100%;
            height: 100%;
        }

        .inner-kpi-full-report {
            display: flex;
            flex-flow: column;
        }

        .daily-sales-segment {
            width:100%;
            text-align: center;
        }

        .inner-daily-sales {
            display: flex;
            flex-flow: row wrap;
            justify-content: space-evenly;
        }

        table {
            width: 100%;
        }

        .report-totals .row {
            display: flex;
            flex-flow: row;
            justify-content: space-between;
            margin: 0 30%;
        }
    }

    @media screen and (max-width: 999px) {
        h2, td, th {
            font-size: 60%;
        }

        @media screen and (min-width: 768px) {
            .report-table {
                max-width: 35em;
                min-width: 20em;
            }
        }

        @media screen and (max-width: 767px) {
            .report-table {
                width: 90%;
            }
        }
    }

    @media screen and (min-width: 1000px) {
        h2, td, th {
            font-size: 80%;
        }

        .report-table {
            width: 22.5em;
        }
    }

</style>
