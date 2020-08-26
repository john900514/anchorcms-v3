<template>
    <button-tank
        :loading="loading"
        :error="error"
    ></button-tank>
</template>

<script>
import ButtonTank from '../../../presenters/widgets/mega-buttons/ComponentsOfMegaButtons';
    export default {
        name: "MegaButtonContainer",
        components: {
            ButtonTank
        },
        data() {
            return {
                loading: true,
                error: ''
            }
        }
    }
</script>

<style scoped>

</style>
