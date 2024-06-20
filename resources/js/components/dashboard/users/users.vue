<template>
    <div class="flex flex-col h-full max-h-[calc(100vh-60px)]">
        <div class="flex flex-col-reverse overflow-y-auto hide-scrollbar">
            <card-user v-for="user in users" :user="user"/>
        </div>
    </div>
</template>

<script>
import CardUser from "./card-user.vue";

export default {
    components: {CardUser},
    props: {
        users: {
            type: Array,
            default: []
        }
    },
    watch: {
        '$store.state.channel.currentChannel': {
            immediate: true,
            handler(newChannel) {
                if (newChannel && window.Echo) {
                    window.Echo.join(`channel.${newChannel.id}`)
                        .here((users) => {
                            this.users.forEach(user => user.online = false);

                            users.forEach(user => {
                                this.users.find((u) => u.id === user.id).online = true;
                            });
                        })
                        .joining((user) => {
                            this.users.find((u) => u.id === user.id).online = true;
                        })
                        .leaving((user) => {
                            this.users.find((u) => u.id === user.id).online = false;
                        });
                }
            }
        }
    }
}
</script>
