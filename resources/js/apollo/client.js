import ApolloClient from 'apollo-boost';
import ErrorHandler from './error-handler';

const apolloClient = new ApolloClient({
    uri: '/graphql',
    headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest',
    },
    onError: ErrorHandler
});

apolloClient.defaultOptions = {
    watchQuery: {
        fetchPolicy: 'network-only',
    },
    query: {
        fetchPolicy: 'network-only',
    },
};
export default apolloClient;
