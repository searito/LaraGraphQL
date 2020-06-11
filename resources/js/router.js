import VueRouter from 'vue-router';
import Transactions from './views/transactions/index';
import Accounts from './views/accounts/index';
import CrearCuenta from './views/accounts/create';
import EditarCuenta from './views/accounts/edit';

const routes = [
    {
        path: '/transactions',
        component: Transactions
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
    }
];
export default new VueRouter({
    mode: 'history',
    routes
});
