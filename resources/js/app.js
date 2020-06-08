require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueApollo from 'vue-apollo'
import Router from './router';
import ApolloClient from './apollo/client';

Vue.use(VueRouter);
Vue.use(VueApollo);

const apolloProvider = new VueApollo({
    defaultClient: ApolloClient,
});

const app = new Vue({
    el: '#app',
    router: Router,
    apolloProvider,
});
