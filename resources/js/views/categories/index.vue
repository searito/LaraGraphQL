<template>
    <div class="w-full">
        <div class="flex justify-between">
            <h2 class="h2">Categorias</h2>
            <button class="button-primary" @click="goToCreate">
                Crear
            </button>
        </div>

        <SimpleTable :headings="headings"
                     :data="categories"
                     :loading="loading"
                     @editRecord="edit"
                     @deleteRecord="deleting"
        />
    </div>
</template>

<script>
    import SimpleTable from './../../components/tables/simple-table';
    import Categories from './../../graphql/categories/categories.graphql';
    import EliminarCategoria from './../../graphql/categories/delete-category.graphql';
    import Swal from 'sweetalert2/dist/sweetalert2.js'
    import 'sweetalert2/src/sweetalert2.scss'

    export default {
        data(){
            return {
                headings: [
                    'ID',
                    'Nombre',
                ],
                categories: [],
                loading: true,
            }
        },
        name: "index",
        components: {
            SimpleTable,
        },
        created(){
            this.getCategories();
        },
        methods: {
            async getCategories(){
                const response = await this.$apollo.query({
                    query: Categories,
                    variables: {
                        first: 20,
                        page: 1
                    }
                });
                this.categories = response.data.categories.data.map(item => {
                    return {
                        'id': item.id,
                        'name': item.name,
                    };
                });
                this.loading = false;
            },
            goToCreate(){
                this.$router.push('/categories/create');
            },
            edit(record){
                this.$router.push(`/categories/${record.id}/edit`);
            },
            deleting(record){
                Swal.fire({
                    title: 'Estas Seguro?',
                    text: `Quieres eliminar la categoría ${record.name}, una vez hecho no hay marcha atrás`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si'
                }).then((result) => {
                    if (result.value) {
                        this.loading = true;
                        return this.$apollo.mutate({
                            mutation: EliminarCategoria,
                            variables: {
                                id: record.id
                            }
                        });
                        this.getCategories();
                    }
                }).then(response => {
                    this.getCategories();
                })
            }
        }
    }
</script>

<style scoped>

</style>
