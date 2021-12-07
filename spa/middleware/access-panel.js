export default function ({ store, redirect }) {

    if(!localStorage.getItem('admin')){
        return redirect('/')
    }

}