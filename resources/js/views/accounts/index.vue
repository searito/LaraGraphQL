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
        />
    </div>
</template>

<script>
    import SimpleTable from './../../components/tables/simple-table';
    import Accounts from './../../graphql/accounts/accounts.graphql';

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
        }
    }
</script>

<style scoped>

</style>
