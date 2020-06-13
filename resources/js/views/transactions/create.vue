<template>
    <div class="w-full">
        <div class="mb-4 flex justify-between">
            <label for="" class="block text-gray-700 text-sm font-bold mb-2 w-1/2 pr-4 pl-4">
                Cuenta

                <select name="cuenta"
                        id="cuenta"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        v-model="form.account_id"
                        style="margin-top: 1em;"
                >
                    <option :value="account.id" v-for="account in accounts">{{ account.name }}</option>
                </select>
            </label>


            <label for="" class="block text-gray-700 text-sm font-bold mb-2 w-1/2 pr-4 pl-4">
                Categoría

                <select name="categoria"
                        id="categoria"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        v-model="form.category_id"
                        style="margin-top: 1em;"
                >
                    <option :value="category.id" v-for="category in categories">{{ category.name }}</option>
                </select>
            </label>
        </div>

        <div class="mb-4 flex justify-between">
            <label for="" class="block text-gray-700 text-sm font-bold mb-2 w-1/2 pr-4 pl-4">
                Tipo

                <select name="tipo"
                        id="tipo"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        v-model="form.type"
                        style="margin-top: 1em;"
                >
                    <option :value="type.id" v-for="type in types">{{ type.name }}</option>
                </select>
            </label>

            <label for="" class="block text-gray-700 text-sm font-bold mb-2 w-1/2 pr-4 pl-4">
                Monto

                <input type="number"
                       name="monto"
                       id="monto"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="0.00"
                       min="0.00"
                       v-model="form.amount"
                       style="margin-top: 1em;"
                />
            </label>
        </div>

        <div class="mb-4 pl-4">
            <Loading :loading="loading"/>

            <button v-if="!loading" class="button-primary" @click="submit">
                Crear
            </button>
        </div>


        <ErrorToast v-if="this.errors" :errors="this.errors"/>
    </div>
</template>

<script>
    import AccountsCategories from './../../graphql/transactions/create-transaction-data.graphql';
    import Loading from './../../components/common/loading';

    export default {
        name: "create",
        data(){
            return {
                accounts: [],
                categories: [],
                loading: true,
                form:{
                    account_id: 0,
                    account_name: null,
                    category_id: 0,
                    category_name: null,
                    type: null,
                    amount: 0,
                },
                types: [
                    {
                        id: 'INCOME',
                        name: 'Ingreso'
                    },
                    {
                        id: 'EXPENSE',
                        name: 'Gasto'
                    }
                ],
                errors: []
            }
        },
        components: {
            Loading,
        },
        created() {
            this.getData();
        },
        methods: {
            async getData(){
                const response = await this.$apollo.query({
                    query: AccountsCategories
                });
                this.categories = response.data.categories.data.map(item => {
                    return {
                        'id': item.id,
                        'name': item.name,
                    };
                });

                this.accounts = response.data.accounts.data.map(item => {
                    return {
                        'id': item.id,
                        'name': item.name,
                    };
                });
                this.loading = false;
            },
            submit(){},
        }
    }
</script>

<style scoped>

</style>
