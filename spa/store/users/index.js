
export const state = () => ({

    dialog: {
        show: false,
        add: false,
        id: null,
    },
    users: [],
    roles: [],

});

export const mutations = {

    setUsers: (state, payload) => {
        state.users = payload;
    },

    setRoles: (state, payload) => {
        state.roles = payload;
    },

    showDialog: (state, payload) => {
        state.dialog.show = !state.dialog.show;
        state.dialog.id = payload.id;
        state.dialog.add = payload.add;
    },

    updateArray: (state,payload) => {

        let user = state.users.find(item => item.id === payload.id);

        if(payload.input == 'role_id'){

            let role = state.roles.find(item => item.id === payload.value);
            user.role = role;
            return;

        }

        user[payload.input] = payload.value;

    },

};

export const actions = {

    async getUsers({commit}){

        let url = `/api/users`;

        let resp = await this.$axios.get(url, {params: {all:true}});

        commit('setUsers', resp.data.data); 

    },

    async getRoles({commit}){

        let url = `/api/roles`;

        let resp = await this.$axios.get(url);

        commit('setRoles', resp.data.data); 

    },

};

export const getters = {

    getUsers: (state) => {
        return state.users;
    },

    getRoles: (state) => {
        return state.roles;
    },

    getDialog: (state) => {
        return state.dialog;
    },

};