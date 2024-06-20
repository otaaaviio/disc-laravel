import api from "../services/api.js";
import {toast} from "vue3-toastify";

export const guilds = {
    namespaced: true,
    state: {
        guilds: [],
        currentGuild: null,
    },
    getters: {
        getGuilds: state => state.guilds,
        getCurrentGuild: state => state.currentGuild,
    },
    mutations: {
        setGuilds(state, guilds) {
            state.guilds = guilds;
        },
        setCurrentGuild(state, guild) {
            state.currentGuild = guild;
        }
    },
    actions: {
        index({commit, dispatch}) {
            api.get('/guilds')
                .then((res) => {
                    commit('setGuilds', res.data.guilds);
                    if (res.data.guilds.length > 0) {
                        dispatch('show', res.data.guilds[0].id);
                    }
                })
                .catch((err) => {
                    if (err.response.status === 404)
                        toast.warning('No guilds found, register one!');
                    else
                        toast.error('An error occurred');
                })
        },
        show({commit}, id) {
            api.get(`/guilds/${id}`)
                .then((res) => {
                    commit('setCurrentGuild', res.data.guild);
                })
                .catch(() => {
                    toast.error('An error occurred');
                })
        },
        delete({dispatch}, id) {
            api.delete(`/guilds/${id}`)
                .then(() => {
                    dispatch('index');
                    toast.success('Guild deleted successfully');
                })
                .catch(() => {
                    toast.error('An error occurred');
                });
        },
        store({dispatch}, data) {
            api.post('/guilds', data)
                .then(() => {
                    dispatch('index');
                    toast.success('Guild registered successfully');
                })
                .catch(() => {
                    toast.error('An error occurred');
                });
        }
    },
}
