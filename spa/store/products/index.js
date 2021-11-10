export const state = () => ({

    dialog: {
        show: false,
        add: false,
        id: null,
    },
    products: [],

});

export const mutations = {

    setProducts: (state, payload) => {
        state.products = payload;
    },

    showDialog: (state, payload) => {
        state.dialog.show = !state.dialog.show;
        state.dialog.id = payload.id;
        state.dialog.add = payload.add;
    },

    updateArray(state,payload){

        let product = state.products.find(item => item.id === payload.id);

        if(payload.input == 'store_id'){

            let store = this.state.stores.stores.find(item => item.id === payload.value);
            product.store = store;

        } 

        else if(payload.input == 'price') {

            let price = Number.parseFloat(payload.value).toFixed(2);
            product[payload.input] = price;

        }
        
        else {

            product[payload.input] = payload.value;

        }

    },

};

export const actions = {




};

export const getters = {

    getProducts: (state) => {
        return state.products;
    },

    getDialog: (state) => {
        return state.dialog;
    },

};