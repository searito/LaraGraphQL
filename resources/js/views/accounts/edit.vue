<template>
    <div class="w-full">
        <div class="mb-4 flex justify-between">
            <label for="" class="block text-gray-700 text-sm font-bold mb-2 w-1/2 pr-4 pl-4">
                Nombre De Cuenta

                <input type="text"
                       name="name"
                       id="name"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="Nombre De Cuenta"
                       v-model="form.name"
                       style="margin-top: 1em;"
                />
            </label>


            <label for="" class="block text-gray-700 text-sm font-bold mb-2 w-1/2 pr-4 pl-4">
                Saldo

                <input type="number"
                       name="balance"
                       id="balance"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="0.00"
                       min="0"
                       v-model="form.balance"
                       style="margin-top: 1em;"
                       readonly
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
    import Cuenta from './../../graphql/accounts/account.graphql';
    import ActualizarCuenta from './../../graphql/accounts/update-account.graphql';
    import Loading from './../../components/common/loading';

    export default {
        name: "edit",
        data(){
            return {
                form: {
                    name: null,
                    balance: 0,
                },
                errors: null,
                loading: false,
                account: null,
            }
        },
        components: {
            Loading,
        },
        created() {
            this.getAccount();
        },
        methods: {
            async getAccount(){
                this.loading = true;
                try{
                    const response = await this.$apollo.query({
                        query: Cuenta,
                        variables: {
                            id: this.$route.params.id,
                        }
                    });

                    this.loading = false;
                    var data = response.data.account;
                    this.account = response.data.account;
                    this.form.name = data.name;
                    this.form.balance = data.balance;
                    console.info(this.account);
                }catch(e){
                    console.error(e);
                }
            },

            async submit(){
                this.errors = null;
                this.loading = true;
                try{
                    const response = await this.$apollo.mutate({
                        mutation: ActualizarCuenta,
                        variables: {
                            id: this.account.id,
                            input: {
                                name: this.form.name,
                            }
                        }
                    });

                    this.loading = false;
                    if (response.data){
                        return this.$router.push('/accounts');
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
