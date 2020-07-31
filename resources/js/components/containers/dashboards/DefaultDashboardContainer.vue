<template>
    <default-dashboard
        :title="dashboardTitle"
        :error="errorMsg"
        :loading="loading"
        :top-widgets="topWidgets"
        :left-widgets="leftWidgets"
        :right-widgets="rightWidgets"
    ></default-dashboard>
</template>

<script>
    import DefaultDashboard from '../../presenters/dashboards/DefaultDashboardComponent';
    import { mapGetters,  mapActions } from 'vuex'

    export default {
        name: "DefaultDashboardContainer",
        components: {
            DefaultDashboard
        },
        props: ['clientName'],
        watch: {
            widgets(gets) {
                this.processWidgets(gets);
                this.loading = false;
            },
            widgetsErrorMsg(msg) {
                console.log('Received widget error - '+ msg);
                this.error = true;
                this.errorMsg = msg;
                this.loading = false;
            }
        },
        data() {
            return {
                error: false,
                errorMsg: '',
                loading: false,
                topWidgets: [],
                leftWidgets: [],
                rightWidgets: [],
                availableWidgets: []
            };
        },
        computed: {
            ...mapGetters({
                widgets: 'dashboard/widgets',
                widgetsErrorMsg: 'dashboard/widgetsErrorMsg'
            }),
            dashboardTitle() {
                return `Reporting for ${this.clientName}`;
            },
        },
        methods: {
            getAvailableWidgets() {
                this.loading = true;
                this.getUserAvailableWidgets();
            },
            processWidgets(widgets) {
                console.log('Processing widgets - ', widgets);
                this.availableWidgets = widgets['available'];

                for(let x in widgets['default']) {
                    switch(widgets['default'][x]['location']) {
                        case 'left':
                            this.leftWidgets.push(widgets['default'][x])
                            break;

                        case 'right':
                            this.rightWidgets.push(widgets['default'][x])
                            break;

                        case 'center':
                        default:
                            this.topWidgets.push(widgets['default'][x])
                    }
                }
                //this.processedWidgets = widgets['default'];

            },
            ...mapActions({
                getUserAvailableWidgets: 'dashboard/getUserAvailableWidgets'
            }),
        },
        mounted() {
            this.getAvailableWidgets();
            console.log('Default Dashboard loaded!');
        }
    }
</script>

<style scoped>

</style>
