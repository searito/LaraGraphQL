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
                <tr v-for="item in data">
                    <td class="p-2 border border-gray-400" v-for="row in item">{{ row }}</td>
                    <td class="p-2 border border-gray-400 w-1/4 text-center">
                        <button class="button-primary">Editar</button>
                        <button class="button-danger">Borrar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "simple-table",
        props: ['headings', 'data']
    }
</script>

<style scoped>

</style>
