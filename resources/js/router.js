import VueRouter from 'vue-router';
import Transactions from './views/transactions/index';
import Accounts from './views/accounts/index';
import CrearCuenta from './views/accounts/create';

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
    }
];
export default new VueRouter({
    mode: 'history',
    routes
});
