
export const state = () => ({

    paginationSection: null,
    pagination : {
        total : 0,
        page: 1,
        items: 0
    },


});

export const mutations = {

    changePaginationSection: (state,payload) => {
        state.paginationSection = payload.section;
    },

    pagination: (state, payload) => {
        state.pagination.total = payload.total_pages;
        state.pagination.page = payload.current_page;
        state.pagination.items = payload.total;
    },

    changePage: (state, payload) => {
        state.pagination.page = payload;
    },



};

export const actions = {

    setItemsPagination({commit, state}, url){

        commit('setLoading', null, {root: true});

            this.$axios.get(url)
            .then(response=>{

                if (state.paginationSection == 'stores') {
                    commit('stores/setStores', response.data.data, {root: true});
                }	

                commit('showSkeleton', false, {root: true});
                commit('pagination', response.data.meta.pagination);

            })
            .catch(response=>{
                console.log(response)
            })
            .finally(()=>{
                commit('setLoading', null, {root: true});
            })

        // });

    },



};

export const getters = {

    getPagination: (state) => {
        return state.pagination;
    },

    getPaginationSection: (state) => {
        return state.paginationSection;
    },



};