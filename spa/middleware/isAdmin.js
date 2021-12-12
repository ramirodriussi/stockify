export default function ({ $auth, redirect }) {

    if(!$auth.hasScope('Administrador')){
        redirect('/panel');
    }

}