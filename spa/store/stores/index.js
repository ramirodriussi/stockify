
export const state = () => ({

    dialog: {
        show: false,
        add: false,
        id: null,
    },
    stores: [],

});

export const mutations = {

    setStores: (state, payload) => {
        state.stores = payload;
    },

    showDialog: (state, payload) => {
        state.dialog.show = !state.dialog.show;
        state.dialog.id = payload.id;
        state.dialog.add = payload.add;
    },

    updateArray: (state,payload) => {

        let store = state.stores.find(item => item.id === payload.id);
        store[payload.input] = payload.value;

    },

};

export const actions = {

    async getStores({commit}){

        let url = `/api/stores`;

        let resp = await this.$axios.get(url, {params: {all:true}});

        commit('setStores', resp.data.data); 

    }


};

export const getters = {

    getStores: (state) => {
        return state.stores;
    },

    getDialog: (state) => {
        return state.dialog;
    },

};