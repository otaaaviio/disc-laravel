import api from "../services/api.js";
import {toast} from "vue3-toastify";
import ClipboardJS from "clipboard";

export const guilds = {
    namespaced: true,
    state: {
        guilds: [],
        currentGuild: null,
    },
    getters: {
        getGuilds: state => state.guilds,
        getCurrentGuild: state => state.currentGuild,
        getCurrentUserIsAdmin: (state, getters, rootState) => {
            if (!state.currentGuild || !rootState.auth.user)
                return false;

            const AdmUser =  state.currentGuild.members.find(member => member.role === 'Admin');

            return AdmUser?.id === rootState.auth.user.user?.id;
        },
    },
    mutations: {
        setGuilds(state, guilds) {
            state.guilds = guilds;
        },
        setCurrentGuild(state, guild) {
            state.currentGuild = guild;
        },
        clearGuilds(state) {
            state.guilds = [];
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
                    if (err.response.status === 404) {
                        toast.warning('No guilds found, register one!');
                        commit('clearGuilds');
                    }
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
        delete({dispatch, state, rootState}, id) {
            api.delete(`/guilds/${id}`)
                .then(() => {
                    dispatch('index');
                    state.currentGuild = null;
                    if(state.guilds.length === 0)
                        rootState.message.messages = [];
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
        },
        joinViaCode({dispatch, state}, code) {
            api.post('/guilds/entry', {invite_code: code})
                .then(() => {
                    dispatch('index');
                    toast.success('Joined guild successfully');
                })
                .catch((err) => {
                    if(err.response?.data?.message === 'Invalid invite code')
                        toast.error('Invalid code');
                    else
                        toast.error('Invalid code');
                });
        },
        getInviteCode({state}, id) {
            if(!id) return;
            api.get(`/guilds/inviteCode/${id}`)
                .then(async (res) => {
                    new ClipboardJS(document.body, {
                        text: function() {
                            return res.data.invite_code;
                        }
                    });
                    setTimeout(() => {
                        toast.success('Invite code: ' + res.data.invite_code + ' copied to clipboard');
                    }, 500);
                })
                .catch(() => {
                    toast.error('An error occurred');
                });
        },
        leave({dispatch, state}, id) {
            api.post(`/guilds/leave/${id}`)
                .then(() => {
                    dispatch('index');
                    if(state.currentGuild.length === 0)
                        state.currentGuild = null;
                    toast.success('Left guild successfully');
                })
                .catch(() => {
                    toast.error('An error occurred');
                });
        }
    },
}
