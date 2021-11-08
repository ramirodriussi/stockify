
export const state = () => ({

    snackbar: {
        color: '',
        text: '',
        show: false,
    },
    user: {},
    logo: {
        url: null,
        formData: '',
    },
    profileDialog: false,
    skeleton: false,
    loading: false,

});

export const mutations = {

    showSnackbar: (state, payload) => {

        if(payload){
            state.snackbar.color = payload.color;
            state.snackbar.text = payload.text;
        }

        state.snackbar.show = !state.snackbar.show;

    },	

    setUser: (state, payload) => {
        state.user = payload;
    },

    updateUser: (state, payload) => {
        state.user.name = payload.name;
    },

    setProfileDialog: (state) => {
        state.profileDialog = !state.profileDialog;
    },

    showSkeleton: (state, payload) => {
        state.skeleton = payload;
    },

    setLoading: (state) => {
        state.loading = !state.loading;
    },

    setLogo: (state, payload) => {
        
        state.user.logo = payload.avatar;

    },


};

export const actions = {

    async setUser({commit, state}){

        let resp = await this.$axios.get('/api/user');

        commit('setUser', resp.data.data);

    },

};

export const getters = {

    getProfileDialog: (state) => {
        return state.profileDialog;
    }

};