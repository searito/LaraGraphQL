const errorHandler = ({graphQLErrors, networkError}) => {
    if (graphQLErrors){
        graphQLErrors.forEach((err) => {
            switch (err.extensions.category) {
                case 'authentication':
                    window.location.href = '/login';
                    break;

                default:
                    console.log(`[GraphQL Error]: ${err}`);
                    break;
            }
        });
    }

    if (networkError){
        console.log(`[Network Error]: ${networkError}`);
    }

    return null;
};

export default errorHandler;
