<template>
    <div class="main-content">
        <default>
            <div class="flex flex-row min-h-[calc(100%)]">
                <div class="w-[20rem] bg-bars">
                    <channels :channels="currentGuild?.channels"/>
                </div>
                <div class="w-full bg-primary flex-grow">
                    <chat/>
                </div>
                <div class="w-[20rem] bg-bars">
                    <users :users="currentGuild?.members"/>
                </div>
            </div>
        </default>
    </div>
</template>

<script>
import Default from "../layouts/default.vue";
import Chat from "../components/dashboard/chat/chat.vue";
import Channels from "../components/dashboard/channels/channels.vue";
import Users from "../components/dashboard/users/users.vue";
import {mapState} from "vuex";

export default {
    components: {
        Users,
        Channels,
        Chat,
        Default
    },
    computed: {
        ...mapState('guilds', ['currentGuild']),
    },
};
</script>

<style scoped>
.main-content {
    display: flex;
}
</style>
