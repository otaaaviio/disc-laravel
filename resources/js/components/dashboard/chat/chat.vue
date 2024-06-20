<template>
    <div class="flex flex-col h-full max-h-[calc(100vh-60px)]">
        <div ref="messageContainer" class="flex flex-col overflow-y-auto hide-scrollbar">
            <message v-for="message in messages" :message="message"/>
        </div>
        <footer class="w-full p-2">
            <form @submit.prevent="sendMessage" class="flex">
                <input type="text"
                       v-model="message"
                       class="bg-gray-200 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:outline-none"
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
    },
    methods: {
        ...mapMutations('message', ['pushMessage']),
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messageContainer;
                container.scrollTop = container.scrollHeight;
            });
        },
        async sendMessage() {
            if(this.message === '') return;

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
                        .error((error) => {
                            console.error(error);
                        });
                }
            }
        }
    }
}
</script>
