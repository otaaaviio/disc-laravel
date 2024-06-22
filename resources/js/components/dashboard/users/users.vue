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
    methods: {
        sortUsers() {
            this.users.sort((a, b) => {
                if (a.online && !b.online) return 1;
                if (!a.online && b.online) return -1;
                return 0;
            });
        }
    },
    watch: {
        '$store.state.channel.currentChannel': {
            immediate: true,
            handler(newChannel) {
                if (newChannel && window.Echo) {
                    window.Echo.join(`channel.${newChannel.id}`)
                        .here((users) => {
                            let onlineUsersMap = new Map(users.map(user => [user.id, user]));

                            this.users.forEach(user => {
                                user.online = onlineUsersMap.has(user.id);
                            });

                            this.sortUsers();
                        })
                        .joining((user) => {
                            this.users.find((u) => u.id === user.id).online = true;
                            this.sortUsers();
                        })
                        .leaving((user) => {
                            this.users.find((u) => u.id === user.id).online = false;
                            this.sortUsers();
                        });
                }
            }
        },

    }
}
</script>
