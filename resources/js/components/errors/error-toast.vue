<template>
    <div></div>
</template>

<script>
    export default {
        name: "error-toast",
        props: ['errors'],
        mounted() {
            let {graphQLErrors} = this.errors;

            graphQLErrors.forEach((err) => {
                switch (err.extensions.category) {
                    case 'validation':
                        let error = "";
                        for(let [key, value] of Object.entries(err.extensions.validation)){
                            error += `${value}`;
                        }
                        this.$toasted.show(error, {
                            theme: "toasted-primary",
                            position: "top-right",
                            duration : 3000,
                            /*icon: {
                                name: 'warning'
                            }*/
                        });
                        break;

                    default:
                        console.log(`[GraphQL Error]: ${error}`);
                        break;
                }
            });
        }
    }
</script>

<style scoped>

</style>
