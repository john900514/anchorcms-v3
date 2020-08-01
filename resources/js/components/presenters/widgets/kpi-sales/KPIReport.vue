<template>
    <div class="kpi-report-container">
        <div class="inner-kpi-report">
            <div class="reporting-zone">
                <div class="inner-reporting-zone">
                    <div class="loading-piece" v-if="loading">
                        <div class="inner-loading-piece">
                            <sexy-hurricane-loading
                                loading-msg="Generating KPI Report..."
                            ></sexy-hurricane-loading>
                        </div>
                    </div>
                    <div class="error-piece" v-else-if="(!loading) && (error !== '')">
                        <div class="inner-error-piece">
                            <p>{{ error }}</p>
                        </div>
                    </div>
                    <div class="kpi-piece" v-else>
                        <div class="inner-kpi-piece" v-if="repData !== ''">
                            <div class="kpi-report">
                                <div class="inner-kpi-report" style="text-align: center">
                                    <!-- Daily Sales -->
                                    <div style="width:100%;text-align: center;margin-top: 2em;">
                                        <h2 style="text-align: center;">--- DAILY Sales ---</h2>
                                        <h2 style="text-align: center;">Sales By Market for {{ getDate }}</h2>
                                        <div class="report-table">
                                            <table style="margin:1em auto;">
                                                <thead>
                                                <tr><th v-for="(columnName, cidx) in  repData.daily.headers">{{columnName}}</th></tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(marketData, marketName) in  repData.daily" v-if="(marketName !== 'totals') && (marketName !== 'headers')">
                                                    <td v-for="(mval, mcol) in marketData">{{ mval }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="report-totals">
                                            <h2 style="text-align: center;">DAILY Sales Totals</h2>
                                            <div style="text-align: center;">
                                                <table style="margin:1em auto;">
                                                    <tbody>
                                                    <tr class="row" v-for="(total, col) in repData.daily.totals">
                                                        <td> {{ col }} </td>
                                                        <td>{{ total.toFixed(2) }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Monthly Sales -->
                                    <div style="width:100%;text-align: center;margin-top: 2em;">
                                        <h2 style="text-align: center;">--- MONTHLY Sales ---</h2>
                                        <h2 style="text-align: center;">Sales By Market for {{ getMonth }}, {{ getYear }} </h2>
                                        <div class="report-table">
                                            <table style="margin:1em auto;">
                                                <thead>
                                                <tr><th v-for="(columnName, cidx) in  repData.monthly.headers">{{columnName}}</th></tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(marketData, marketName) in  repData.monthly" v-if="(marketName !== 'totals') && (marketName !== 'headers')">
                                                    <td v-for="(mval, mcol) in marketData">{{ (mcol === 'Market') ? mval : mval.toFixed(2) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="report-totals">
                                            <h2 style="text-align: center;">MONTHLY Sales Totals</h2>
                                            <div style="text-align: center;">
                                                <table style="margin:1em auto;">
                                                    <tbody>
                                                    <tr class="row" v-for="(total, col) in repData.monthly.totals">
                                                        <td> {{ col }} </td>
                                                        <td>{{ total.toFixed(2) }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Promo -->
                                    <div style="width:100%;text-align: center;margin-top: 2em;" v-if="false">
                                        <div class="report-table">
                                            <h2 style="text-align: center;">--- PROMO Sales ---</h2>
                                            <h2 style="text-align: center;">Promo Code Use Daily Totals for {{ getDate }}</h2>
                                            <div style="margin:1em auto;">
                                                <table style="margin:1em auto;">
                                                    <tbody v-if=" repData.promo.daily.length === 0">
                                                    <tr class="row"><td>No Sales Data Yet. Maybe Later!</td></tr>
                                                    </tbody>
                                                    <tbody v-else>
                                                    <tr class="row" v-for="(sales, promo) in repData.promo.daily">
                                                        <td style="margin-right: 3em;">{{promo}}</td>
                                                        <td>{{sales}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="report-table" style="margin:2em auto;">
                                            <h2 style="text-align: center;">Promo Code Sales for {{ getMonth }} {{ getYear }}</h2>
                                            <div style="margin:1em auto;">
                                                <table style="margin:1em auto;">
                                                    <tbody v-if=" repData.promo.monthly.length === 0">
                                                    <tr class="row"><td>No Sales Data Yet. Maybe Later!</td></tr>
                                                    </tbody>
                                                    <tbody v-else>
                                                    <tr class="row" v-for="(sales, promo) in repData.promo.monthly">
                                                        <td>{{promo}}</td>
                                                        <td>{{sales}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SexyHurricaneLoading from '../loading/SexyHurricane';
    export default {
        name: "KPIReport",
        components: {
            SexyHurricaneLoading
        },
        props: ['loading', 'error', 'report'],
        watch: {
            report(stuff) {
                console.log('Recieved a new report! ', stuff);
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
        }
    }
</script>

<style scoped>
    @media screen {
        .kpi-report-container {
            background-color: lightgray;
            border-radius: 0.2em;
        }

        .c-dark-theme .kpi-report-container {
            background-color: #1e1e29;
        }

        .inner-kpi-report {
            padding: 2%;
        }

        .reporting-zone {
            width: 100%;
            height: 100%;
        }

        .inner-reporting-zone
        {
            background-color: #fff;
            border-radius: 0.2em;
            position:relative;
            -webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        }
        .inner-reporting-zone:before, .inner-reporting-zone:after
        {
            content:"";
            position:absolute;
            z-index:-1;
            -webkit-box-shadow:0 0 20px rgba(0,0,0,0.8);
            -moz-box-shadow:0 0 20px rgba(0,0,0,0.8);
            box-shadow:0 0 20px rgba(0,0,0,0.8);
            top:0;
            bottom:0;
            left:10px;
            right:10px;
            -moz-border-radius:100px / 10px;
            border-radius:100px / 10px;
        }
        .inner-reporting-zone:after
        {
            right:10px;
            left:auto;
            -webkit-transform:skew(8deg) rotate(3deg);
            -moz-transform:skew(8deg) rotate(3deg);
            -ms-transform:skew(8deg) rotate(3deg);
            -o-transform:skew(8deg) rotate(3deg);
            transform:skew(8deg) rotate(3deg);
        }

        .c-dark-theme .inner-reporting-zone {
            background-color: #2c2c34;
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
    }

    @media screen and (min-width: 1000px) {
        h2, td, th {
            font-size: 80%;
        }
    }
</style>
