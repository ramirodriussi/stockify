
export const state = () => ({

    searchBoxPaginationActive: false,
    search: '',

});

export const mutations = {

    setSearchBoxPaginationActive: (state,payload) => {
        state.searchBoxPaginationActive = payload;
    },

    setSearch: (state, payload) => {
        state.search = payload;
    },

};

export const actions = {

    //

};

export const getters = {

    getSearch: (state) => {
        return state.search;
    },

};