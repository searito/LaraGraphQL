require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueApollo from 'vue-apollo'
import Router from './router';
import ApolloClient from './apollo/client';
import Toasted from 'vue-toasted';
import ErrorToast from './components/errors/error-toast';

Vue.use(VueRouter);
Vue.use(VueApollo);
Vue.use(Toasted, {
    iconPack: 'material'
});

Vue.component('ErrorToast', ErrorToast);

const apolloProvider = new VueApollo({
    defaultClient: ApolloClient,
});

const app = new Vue({
    el: '#app',
    router: Router,
    apolloProvider,
});
