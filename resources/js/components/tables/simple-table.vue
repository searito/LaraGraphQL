<template>
    <div>
        <table class="table-auto w-full mt-4">
            <thead class="bg-blue-900">
                <tr>
                    <th class="text-white p-2 border-blue-900" v-for="heading in headings">{{ heading }}</th>
                    <th class="text-white p-2 border-blue-900"></th>
                </tr>
            </thead>
            <tbody class="border b-2">
                <tr v-if="!loading" v-for="item in data">
                    <td class="p-2 border border-gray-400" v-for="row in item">{{ row }}</td>
                    <td class="p-2 border border-gray-400 w-1/4 text-center">
                        <button class="button-primary" @click="editRecord(item)">Editar</button>
                        <button class="button-danger">Borrar</button>
                    </td>
                </tr>

                <tr v-if="loading">
                    <td :colspan="columns">
                        <Loading :loading="loading" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import Loading from './../common/loading';

    export default {
        name: "simple-table",
        props: ['headings', 'data', 'loading'],
        components: {
            Loading,
        },
        computed: {
            columns(){
                return this.headings.length + 1;
            }
        },
        methods: {
            editRecord(record){
                this.$emit('editRecord', record);
            },
        }
    }
</script>

<style scoped>

</style>
