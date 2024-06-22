import api from "../services/api.js";
import {toast} from "vue3-toastify";

export const channel = {
    namespaced: true,
    state: {
        currentChannel: null,
    },
    getters: {
        getChannel: state => state.currentChannel,
    },
    mutations: {
        setChannel(state, newChannel) {
            state.currentChannel = newChannel;
        },
    },
    actions: {
        join({commit, rootState, state}, channel) {
            const guild_id = rootState.guilds.currentGuild?.id;
            const channel_id = channel.id;

            if(channel.id === state.currentChannel?.id) return;

            if (state.currentChannel)
                window.Echo.leave(`channel.${state.currentChannel.id}`);

            commit('setChannel', channel)

            if (guild_id && channel_id)
                api.get('/guilds/' + guild_id + '/channels/' + channel_id)
                    .then((res) => {
                        rootState.message.messages = res.data.messages;
                    })
                    .catch(() => {
                        toast.error('An error occurred');
                    })
        },
        store({rootState, dispatch}, data) {
            api.post(`/guilds/${rootState.guilds.currentGuild.id}/channels`, data)
                .then(() => {
                    dispatch('guilds/show', rootState.guilds.currentGuild.id, {root: true});
                    toast.success('Channel created');
                })
                .catch(() => {
                    toast.error('An error occurred');
                });
        }
    },
}
