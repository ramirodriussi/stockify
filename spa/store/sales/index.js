export const state = () => ({

    dialog: {
        show: false,
        add: false,
        id: null,
    },
    sales: [],
    cart: [],
    total: 0,
});

export const mutations = {

    setSales: (state, payload) => {
        // console.log(state.sales);
        state.sales = payload;
    },

    showDialog: (state, payload) => {
        state.dialog.show = !state.dialog.show;
        state.dialog.id = payload.id;
        state.dialog.add = payload.add;
    },

    updateArray: (state, payload) => {

        let sale = state.sales.find(item => item.id === payload.id);

        if(payload.input === 'quantity'){

            // actualizo cantidad

            let product = sale.products.data.find(item => item.id === payload.productId);

            product.quantity = payload.value;

            // actualizo stock

            let cartProduct = state.cart.find(item => item.id === payload.productId);

            product.stock = (payload.operator === '-') ? product.stock - payload.diff : product.stock + payload.diff;
            cartProduct.stock = product.stock;

        }


        // actualizo medio de pago

        if(payload.input == 'payment_type'){

            sale.payment_type = payload.value;

        }

        // sale[payload.input] = payload.value;

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
        state.cart = state.cart.filter(item => item.id != payload.item.id);
    },

    updateItemCart: (state, payload) => {
        state.cart.find(item => item.id === payload.id).quantity = payload.quantity;
    },

    fillCart: (state, payload) => {
        state.cart = payload;
    },

    clearCart: (state) => {
        state.cart = [];
    },

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
            item.stock = resp.data.stock;
            item.quantity = 1;
    
            commit('setItemCart', item);
    
            commit('showSnackbar', {color:'success',text:'Producto agregado al carrito'}, {root:true});

        } catch {
          
            commit('showSnackbar', {color:'error',text:'El código ingresado no existe'}, {root:true});

        }

    },

    // elimijnar roostate y doispatch

    updateItemSaleQuantity({commit, rootState, dispatch}, payload){

        this.$axios.put(`/api/sales/${payload.saleId}`, {input:'quantity', value:payload.quantity, id:payload.id, operator:payload.operator, diff: payload.diff})
        .then(() => {

            commit('updateItemCart', {quantity:payload.quantity,id:payload.id});
            commit('updateArray', {id:payload.saleId, productId: payload.id, input:'quantity', value:payload.quantity, diff:payload.diff, operator: payload.operator});

            // commit('updateArray', {id:payload.saleId,productId:payload.id,input:'stock',value:payload.diff,operator:payload.operator});

            // let page = rootState.pagination.pagination.page;
            // let url = `/api/sales?page=${page}`;
            // dispatch('pagination/setItemsPagination', url, {root:true});

            commit('showSnackbar', {color:'success',text:'Cantidad actualizada correctamente'}, {root:true});

        })
        .catch(()=>{

            commit('showSnackbar', {color:'error',text:'No se pudo actualizar. Intentá nuevamente'}, {root:true});

        })

    },

    updatePaymentType({commit}, payload){

        this.$axios.put(`/api/sales/${payload.saleId}`, {input:'paymentType', value:payload.paymentType})
        .then(()=> {

            commit('updateArray', {id:payload.saleId,input:'payment_type',value:payload.paymentType});
            commit('showSnackbar', {color:'success',text:'Medio de pago actualizado correctamente'}, {root:true});

        })
        .catch(()=>{

            commit('showSnackbar', {color:'error',text:'No se pudo actualizar. Intentá nuevamente'}, {root:true});

        })

    }



};

export const getters = {

    getSales: (state) => {
        return state.sales;
    },

    getDialog: (state) => {
        return state.dialog;
    },

    cartItems: (state) => {

        return state.cart.map(item => {

            return {

               id: item.id,
               product: item.product,
               code: item.code,
               price: item.price,
               quantity: item.quantity,
               stock: item.stock,
               total: Number(item.quantity * item.price).toFixed(2)

            }

        })

    },

    totalCart: (state, getters) => {

        let total = getters.cartItems.reduce((total, item) => total + item.price * item.quantity, 0);

        return Number(total).toFixed(2);

    },

    formatedSales: (state) => {

        let total;

        return state.sales.map(item => {

            total = item.products.data.reduce((total, prod) => total + prod.price * prod.quantity, 0);

            return {

                id: item.id,
                sale_id: item.sale_id,
                payment_type: item.payment_type,
                created_at: item.created_at,
                total: Number(total).toFixed(2)
 
             }

        })

    }

};