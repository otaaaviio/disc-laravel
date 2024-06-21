import api from "../services/api.js";
import router from "../router/router.js";
import {toast} from "vue3-toastify";

export const auth = {
    namespaced: true,
    state: {
        user: JSON.parse(window.localStorage.getItem('user')) ?? null,
        token: window.localStorage.getItem('token') ?? window.sessionStorage.getItem('token')
    },
    getters: {
        user: state => state.user,
        isLogged: state => state.token !== null,
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
        login({commit, dispatch}, user) {
            api.post('/auth/login', user)
                .then(async res => {
                    if (user.remember)
                        window.localStorage.setItem('token', res.data.token);
                    else
                        window.sessionStorage.setItem('token', res.data.token);

                    dispatch('getAuthenticatedUser');

                    router.push('/dashboard').then(() => {
                        toast.success('Login successful');
                    });
                })
                .catch(err => {
                    console.log(err)
                    if (err.response?.status === 401)
                        toast.error('Invalid credentials');
                    else
                        toast.error('An error occurred');
                });
        },
        register({commit}, user) {
            api.post('/auth/register', user)
                .then(res => {
                    window.sessionStorage.setItem('token', res.data.token);
                    commit('setUser', res.data.user);
                    router.push('/dashboard').then(() => {
                        toast.success('Registration successful');
                    });
                })
                .catch(err => {
                    if (err.response?.data?.message === 'The email has already been taken.')
                        toast.error('Email has already been taken.');
                    else
                        toast.error('An error occurred');
                });
        },
        getAuthenticatedUser({commit}) {
            api.get('/auth/user')
                .then(res => {
                    commit('setUser', res.data);
                })
                .catch(() => {
                    commit('clearUser');
                    window.localStorage.removeItem('token');
                    window.sessionStorage.removeItem('token');
                    window.localStorage.removeItem('user');
                });
        },
        logout({commit}) {
            api.post('/auth/logout')
                .then(() => {
                    window.localStorage.removeItem('token');
                    window.sessionStorage.removeItem('token');
                    window.localStorage.removeItem('user');
                    commit('clearUser');
                    router.push('/login').then(() => {
                        toast.success('Logout successful');
                    })
                })
                .catch(() => {
                    toast.error('An error occurred');
                });
        },
    }
};
