import api from "../services/api.js";
import {toast} from "vue3-toastify";

export const message = {
    namespaced: true,
    state: {
        messages: [],
    },
    getters: {
        getMessages: state => state.messages,
    },
    mutations: {
        setMessages(state, newMessages) {
            state.messages = newMessages;
        },
        pushMessage(state, message) {
            state.messages.push(message);
        },
        clearMessages(state) {
            state.messages = [];
        }
    },
    actions: {
        store({commit, rootState, state}, message) {
            const guild_id = rootState.guilds.currentGuild?.id;
            const channel_id = rootState.channel.currentChannel?.id;

            if (guild_id && channel_id)
                api.post(`/guilds/${guild_id}/channels/${channel_id}/messages`, {content: message})
                    .then((res) => {
                    })
                    .catch(() => {
                        toast.error('An error occurred');
                    })
        },
    },
}
