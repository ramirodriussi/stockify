export const state = () => ({

    dialog: {
        show: false,
        add: false,
        id: null,
    },
    sales: [],
    cart: [],
});

export const mutations = {

    setSales: (state, payload) => {
        state.sales = payload;
    },

    showDialog: (state, payload) => {
        state.dialog.show = !state.dialog.show;
        state.dialog.id = payload.id;
        state.dialog.add = payload.add;
    },

    updateArray: (state,payload) => {

        let sale = state.sales.find(item => item.id === payload.id);
        sale[payload.input] = payload.value;

    },

    setItemCart: (state, payload) => {

        let itemExists = state.cart.find(item => item.id === payload.id);

        console.log(itemExists);

        if(itemExists){
            itemExists.quantity += payload.quantity;
        } else {
            state.cart.push(payload);
        }

    },

    deleteItemCart: (state, payload) => {
        state.cart = state.cart.filter(item => item.id != payload.item);
    },

    updateItemCart: (state, payload) => {
        state.cart.find(item => item.id === payload.id).quantity = payload.quantity;
    },

    fillCart: (state, payload) => {
        state.cart = payload;
    },

    clearCart: (state) => {
        state.cart = [];
    }

};

export const actions = {

    async getSales({commit}){

        let url = `/api/sales`;

        let resp = await this.$axios.get(url, {params: {all:true}});

        commit('setSales', resp.data.data); 

    },

    async setItemCart({commit}, payload){

        try {
            
            const resp = await this.$axios.get(`/api/products/code/${payload.code}`);

            let item = {};

            item.product = resp.data.product;
            item.id = resp.data.id;
            item.price = resp.data.price;
            item.code = resp.data.code;
            item.quantity = 1;
    
            commit('setItemCart', item);
    
            commit('showSnackbar', {color:'success',text:'Producto agregado al carrito'}, {root:true});

        } catch (error) {
          
            commit('showSnackbar', {color:'error',text:'El código ingresado no existe'}, {root:true});

        }

    },



};

export const getters = {

    getSales: (state) => {
        return state.sales;
    },

    getDialog: (state) => {
        return state.dialog;
    },

};