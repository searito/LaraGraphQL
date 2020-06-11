<template>
    <div class="w-full">
        <div class="flex justify-between">
            <h2 class="h2">Cuentas</h2>
            <button class="button-primary" @click="goToCreate">
                Crear
            </button>
        </div>

        <SimpleTable :headings="headings"
                     :data="accounts"
                     :loading="loading"
                     @editRecord="edit"
                     @deleteRecord="deleting"
        />
    </div>
</template>

<script>
    import SimpleTable from './../../components/tables/simple-table';
    import Accounts from './../../graphql/accounts/accounts.graphql';
    import BorrarCuenta from './../../graphql/accounts/delete.graphql';
    import Swal from 'sweetalert2/dist/sweetalert2.js'
    import 'sweetalert2/src/sweetalert2.scss'

    export default {
        data(){
            return {
                headings: [
                    'ID',
                    'Nombre',
                    'Saldo',
                ],
                accounts: [],
                loading: true,
            }
        },
        name: "index",
        components: {
            SimpleTable,
        },
        created(){
            this.getAccounts();
        },
        methods: {
            async getAccounts(){
                const response = await this.$apollo.query({
                    query: Accounts,
                    variables: {
                        first: 20,
                        page: 1
                    }
                });
                this.accounts = response.data.accounts.data.map(item => {
                    return {
                        'id': item.id,
                        'name': item.name,
                        'balance': item.balance,
                    };
                });
                this.loading = false;
            },
            goToCreate(){
                this.$router.push('/accounts/create');
            },
            edit(record){
                this.$router.push(`/accounts/${record.id}/edit`);
            },
            deleting(record){
                Swal.fire({
                    title: 'Estas Seguro?',
                    text: `Quieres eliminar la cuenta ${record.name}, una vez hecho no hay marcha atrás`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si'
                }).then((result) => {
                    if (result.value) {
                        this.loading = true;
                        return this.$apollo.mutate({
                            mutation: BorrarCuenta,
                            variables: {
                                id: record.id
                            }
                        });
                        this.getAccounts();
                    }
                }).then(response => {
                    this.getAccounts();
                })
            }
        }
    }
</script>

<style scoped>

</style>
