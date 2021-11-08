export default function ({ store, redirect }) {

    console.log('middle access panel');

    if(!localStorage.getItem('admin')){
        return redirect('/')
    }

}