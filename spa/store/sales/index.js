export const state = () => ({

    dialog: {
        show: false,
        add: false,
        id: null,
    },
    sales: [],

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

};

export const actions = {

    async getSales({commit}){

        let url = `/api/sales`;

        let resp = await this.$axios.get(url, {params: {all:true}});

        commit('setSales', resp.data.data); 

    }


};

export const getters = {

    getSales: (state) => {
        return state.sales;
    },

    getDialog: (state) => {
        return state.dialog;
    },

};