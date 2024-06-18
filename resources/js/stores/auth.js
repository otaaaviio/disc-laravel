export const auth = {
    namespaced: true,
    state: {
        user: JSON.parse(window.localStorage.getItem('user')) ?? null,
    },
    getters: {
        user: state => state.user,
        isLogged: state => state.user !== null,
    },
    mutations: {
        setUser(state, user) {
            window.localStorage.setItem('user', JSON.stringify(user));
            state.user = user;
        },
        clearUser(state) {
            state.user = null;
        }
    },
    actions: {
        login({ commit }, user) {
            commit('setUser', user);
        },
        logout({ commit }) {
            commit('clearUser');
        }
    }
};
