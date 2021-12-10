
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
    dialog: false,
    isAdmin: false,

});

export const mutations = {

    showSnackbar: (state, payload) => {

        if(payload){
            state.snackbar.color = payload.color;
            state.snackbar.text = payload.text;
        }

        state.snackbar.show = !state.snackbar.show;

    },	

    setAdmin: (state, payload) => {
        state.isAdmin = payload;
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

    setDialog: (state) => {
        state.dialog = !state.dialog;
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

    async setUser({commit}){

        let resp = await this.$axios.get('/api/user/profile');

        commit('setUser', resp.data);

    },

};

export const getters = {

    getProfileDialog: (state) => {
        return state.profileDialog;
    },

    getDialog: (state) => {
        return state.dialog;
    }

};