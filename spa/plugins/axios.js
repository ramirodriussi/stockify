export default function ({ $axios, redirect, store, $auth }) {

    $axios.onError(error => {

        const code = parseInt(error.response && error.response.status)

        if (code === 401) {
          
            // console.log('axios unauthenticated');

            store.commit('showSnackbar', {color:'error', text: 'La sesión ha expirado.'});

            if($auth.loggedIn){

              $auth.logout();

            }

            // redirect('/');

        }

    })
}



