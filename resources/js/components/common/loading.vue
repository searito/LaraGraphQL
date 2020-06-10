<template>
    <div class="text-center my-4">
        <beat-loader :loading="loading"
                     :size="10"
                     :sizeUnit="unit"
                     :color="color"
        />
    </div>
</template>

<script>
    import { BeatLoader } from '@saeris/vue-spinners';

    export default {
        name: "loading",
        props: ['loading'],
        components: {
            BeatLoader
        },
        data(){
            return{
                color: "#2a4365",
                unit: "px",
            }
        }
    }
</script>

<style scoped>

</style>
