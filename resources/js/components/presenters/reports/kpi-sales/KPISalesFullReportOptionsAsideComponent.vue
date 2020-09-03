<template>
    <div class="kpi-section">
        <div class="inner-kpi-section">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="date-input" style="padding: 0 12px;">Report Date</label>
                    <div class="col-md-9">
                        <input class="form-control" id="date-input" type="date" name="date-input" placeholder="date" v-model="selectedReportDate"><span class="help-block">Please enter a valid date</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "KPISalesFullReportOptionsAsideComponent",
    props: ['reportDate'],
    watch: {
        reportDate(date) {
            this.setReportDate(date);
        },
        selectedReportDate(date) {
            this.$emit('date-changed', date);
        }
    },
    methods: {
        setReportDate(date) {
            this.selectedReportDate = date;
        }
    },
    data() {
        return {
            selectedReportDate: ''
        }
    },
    mounted() {
        this.setReportDate(this.reportDate);
    }
}
</script>

<style scoped>

</style>
