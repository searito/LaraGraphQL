<template>
    <div class="w-full">
        <div class="flex justify-between">
            <h2 class="h2">Transacciones</h2>
            <button class="button-primary" @click="goToCreate">
                Crear
            </button>
        </div>

        <SimpleTable :headings="headings"
                     :data="transactions"
                     :loading="loading"
                     @editRecord="edit"
                     @deleteRecord="deleting"
        />
    </div>
</template>

<script>
    import Transacciones from './../../graphql/transactions/transactions.graphql';
    import SimpleTable from './../../components/tables/simple-table';
    export default {
        data(){
            return {
                headings: [
                    'ID',
                    'Cuenta',
                    'Tipo',
                    'Cantidad',
                    'Descripción',
                    'Saldo'
                ],
                transactions: [],
                loading: true,
            }
        },
        components: {
            SimpleTable
        },
        created() {
            this.getData();
        },
        methods: {
            async getData(){
                const response = await this.$apollo.query({
                    query: Transacciones,
                    variables: {
                        first: 20,
                        page: 1
                    }
                });
                this.transactions = response.data.transactions.data.map(item => {
                    return {
                        'id': item.id,
                        'account': item.account.name,
                        'type': item.type,
                        'amount': '$ '+ item.amount,
                        'description': item.description,
                        'balance': '$ '+ item.account.balance,
                    };
                });
                this.loading = false;
            },
            goToCreate(){
                this.$router.push('/transactions/create');
            },
            edit(record){},
            deleting(record){},
        }
    }
</script>

<style scoped>

</style>
