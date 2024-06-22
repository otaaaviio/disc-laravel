import api from "../services/api.js";
import {toast} from "vue3-toastify";

export const message = {
    namespaced: true,
    state: {
        messages: [],
    },
    mutations: {
        clearMessages(state) {
            state.messages = [];
        },
        pushMessage(state, message) {
            state.messages.push(message);
        },
        deleteMessage(state, message) {
            state.messages = state.messages.filter(m => m.id !== message.id);
        },
    },
    actions: {
        store({rootState}, message) {
            const guild_id = rootState.guilds.currentGuild?.id;
            const channel_id = rootState.channel.currentChannel?.id;

            if (guild_id && channel_id)
                api.post(`/guilds/${guild_id}/channels/${channel_id}/messages`, {content: message})
                    .then(() => {
                    })
                    .catch(() => {
                        toast.error('An error occurred');
                    })
        },
        delete({rootState}, id) {
            const guild_id = rootState.guilds.currentGuild?.id;
            const channel_id = rootState.channel.currentChannel?.id;

            if (guild_id && channel_id)
                api.delete(`/guilds/${guild_id}/channels/${channel_id}/messages/${id}`)
                    .then(() => {
                        toast.success('Message deleted successfully');
                    })
                    .catch(() => {
                        toast.error('An error occurred');
                    });
        }
    },
}
