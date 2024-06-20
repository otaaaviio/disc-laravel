<template>
    <div class="flex flex-col h-full max-h-[calc(100vh-60px)]">
        <div ref="messageContainer" class="flex flex-col overflow-y-auto hide-scrollbar flex-grow">
            <message v-for="message in messages" :message="message"/>
        </div>
        <footer class="w-full p-2 ">
            <form @submit.prevent="sendMessage" class="flex">
                <input type="text"
                       :disabled="!currentChannel"
                       :class="{'btn-disabled': !currentChannel}"
                       v-model="message"
                       class="bg-bars text-white text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:outline-none focus:ring-indigo-500"
                       placeholder="Write something cool..."/>
            </form>
        </footer>
    </div>
</template>

<script>
import Message from "./message.vue";
import {mapMutations, mapState} from "vuex";

export default {
    components: {Message},
    data() {
        return {
            message: ''
        }
    },
    computed: {
        ...mapState('message', ['messages']),
        ...mapState('channel', ['currentChannel']),
    },
    methods: {
        ...mapMutations('message', ['pushMessage']),
        ...mapMutations('message', ['deleteMessage']),
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messageContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },
        async sendMessage() {
            if (this.message === '') return;

            await this.$store.dispatch('message/store', this.message);
            this.message = '';
        },
    },
    watch: {
        '$store.state.channel.currentChannel': {
            immediate: true,
            handler(newChannel) {
                if (newChannel && window.Echo) {
                    window.Echo.private(`channel.${newChannel.id}`)
                        .listen('.message', (res) => {
                            this.pushMessage(res);
                            this.scrollToBottom();
                        })
                        .listen('.message-deleted', (msg) => {
                            this.deleteMessage(msg);
                        })
                }
            }
        }
    }
}
</script>
