<template>
    <div class="w-full">
        <div class="mb-4 flex justify-between">
            <label for="" class="block text-gray-700 text-sm font-bold mb-2 w-1/2 pr-4 pl-4">
                Categoría

                <input type="text"
                       name="name"
                       id="name"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="Nombre De Categoría"
                       v-model="form.name"
                       style="margin-top: 1em;"
                />
            </label>
        </div>

        <div class="mb-4 pl-4">
            <Loading :loading="loading"/>

            <button v-if="!loading" class="button-primary" @click="submit">
                Actualizar
            </button>
        </div>


        <ErrorToast v-if="this.errors" :errors="this.errors"/>
    </div>
</template>

<script>
    import Categoria from './../../graphql/categories/category.graphql';
    import ActualizarCategoría from './../../graphql/categories/update-category.graphql';
    import Loading from './../../components/common/loading';

    export default {
        name: "edit",
        data(){
            return {
                form: {
                    name: null,
                },
                errors: null,
                loading: false,
                category: null,
            }
        },
        components: {
            Loading,
        },
        created() {
            this.getCategory();
        },
        methods: {
            async getCategory(){
                this.loading = true;
                try{
                    const response = await this.$apollo.query({
                        query: Categoria,
                        variables: {
                            id: this.$route.params.id,
                        }
                    });

                    this.loading = false;
                    var data = response.data.category;
                    this.category = response.data.category;
                    this.form.name = data.name;
                }catch(e){
                    console.error(e);
                }
            },

            async submit(){
                this.errors = null;
                this.loading = true;
                try{
                    const response = await this.$apollo.mutate({
                        mutation: ActualizarCategoría,
                        variables: {
                            id: this.category.id,
                            input: {
                                name: this.form.name,
                            }
                        }
                    });

                    this.loading = false;
                    if (response.data){
                        return this.$router.push('/categories');
                    }
                }catch(error){
                    this.errors = error;
                    this.loading = false;
                }
            },
        }
    }
</script>

<style scoped>

</style>
