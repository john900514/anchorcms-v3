<template>
    <button-tank
        :loading="loading"
        :error="error"
        :buttons="buttons"
        @mega-click="emitSelected"
    ></button-tank>
</template>

<script>
    import ButtonTank from '../../../presenters/widgets/mega-buttons/ComponentsOfMegaButtons';
    import { mapGetters,  mapActions } from 'vuex'
    export default {
        name: "MegaButtonContainer",
        components: {
            ButtonTank
        },
        data() {
            return {
                loading: true,
                error: '',
                buttons: [],
            }
        },
        computed: {
            ...mapGetters({
                widgets: 'dashboard/widgets',
            }),
        },
        methods: {
            ...mapActions({
                triggerRightWidgetSwap: 'dashboard/triggerRightWidgetSwap'
            }),
            emitSelected(clickId) {
                console.log('Checking out widgets', this.widgets);
                this.triggerRightWidgetSwap(clickId);
            },
            retrieveWidgetButtons() {
                let mc_btn = {
                    text: 'Email Reports',
                    image: 'https://us17.admin.mailchimp.com/images/brand_assets/logos/mc-freddie-dark.svg',
                    id: "7a977872-5825-4a9a-bf96-4e4d754db20a"
                };

                this.buttons.push(mc_btn);
                let kpi_btn = {
                    text: 'Performance Reports',
                    //image: 'https://cdn.onlinewebfonts.com/svg/img_553433.png',
                    image: 'https://amchorcms-assets.s3.amazonaws.com/UIHere.png',
                    id: "1be5d918-56e6-4b9e-96d2-14e4684dffca"
                };
                this.buttons.push(kpi_btn);

                this.error = '';
                this.loading = false;
            }
        },
        mounted() {
            this.retrieveWidgetButtons();
            console.log('MegaButtonContainer mounted!')
        }
    }
</script>

<style scoped>

</style>
