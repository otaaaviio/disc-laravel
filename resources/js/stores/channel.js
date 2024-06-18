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
            if(channel.id === state.currentChannel?.id) return;

            if (state.currentChannel)
                window.Echo.leave(`chat.${state.currentChannel.id}`);
            //sair do canal antes de entrar em outro
            const guild_id = rootState.guilds.currentGuild?.id;
            const channel_id = channel.id;
            commit('setChannel', channel)

            if (guild_id && channel_id)
                api.get('/guilds/' + guild_id + '/channels/' + channel_id)
                    .then((res) => {
                        rootState.message.messages = res.data.messages.reverse();
                    })
                    .catch(() => {
                        toast.error('An error occurred');
                    })
        },
    },
}
