<template>
    <div class="kpi-report-container">
        <div class="inner-kpi-report">
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
                <div class="inner-kpi-piece">
                    <h1> derp. </h1>
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
        props: ['loading', 'error']
    }
</script>

<style scoped>

</style>
