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
                />
            </label>
        </div>

        <div class="mb-4 pl-4">
            <button class="button-primary" @click="submit">
                Crear
            </button>
        </div>


        <ErrorToast v-if="this.errors" :errors="this.errors"/>
    </div>
</template>

<script>
    import CrearCuenta from './../../graphql/accounts/createAccount.graphql';

    export default {
        name: "create",
        data(){
            return {
                form: {
                    name: null,
                    balance: 0,
                },
                errors: null,
            }
        },
        methods:{
            async submit(){
                this.errors = null;
                try{
                    const response = await this.$apollo.mutate({
                        mutation: CrearCuenta,
                        variables: {
                            input: {
                                name: this.form.name,
                                balance: this.form.balance
                            }
                        }
                    });

                    if (response.data){
                        return this.$router.push('/accounts');
                    }
                }catch(error){
                    this.errors = error;
                }
            },
        }
    }
</script>

<style scoped>

</style>
