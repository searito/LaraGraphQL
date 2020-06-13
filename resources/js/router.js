import VueRouter from 'vue-router';
import Transactions from './views/transactions/index';
import CrearTransaccion from './views/transactions/create';
import Accounts from './views/accounts/index';
import CrearCuenta from './views/accounts/create';
import EditarCuenta from './views/accounts/edit';
import Categorias from './views/categories/index';
import CrearCategoria from './views/categories/create';
import EditarCategoria from './views/categories/edit';

const routes = [
    {
        path: '/transactions',
        component: Transactions
    },
    {
        path: '/transactions/create',
        component: CrearTransaccion
    },
    {
        path: '/accounts',
        component: Accounts
    },
    {
        path: '/accounts/create',
        component: CrearCuenta
    },
    {
        path: '/accounts/:id/edit',
        component: EditarCuenta
    },
    {
        path: '/categories',
        component: Categorias
    },
    {
        path: '/categories/create',
        component: CrearCategoria
    },
    {
        path: '/categories/:id/edit',
        component: EditarCategoria
    },
];
export default new VueRouter({
    mode: 'history',
    routes
});
