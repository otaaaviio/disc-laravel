<template>
    <div class="flex flex-col h-full max-h-[calc(100vh-60px)]">
        <div class="flex flex-col-reverse overflow-y-auto hide-scrollbar">
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
                    window.Echo.channel(`chat.${newChannel.id}`)
                        .listen('SendMessage', (res) => {
                            this.pushMessage(res);
                            console.log(res);
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
