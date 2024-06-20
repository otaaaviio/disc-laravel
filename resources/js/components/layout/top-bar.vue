<template>
    <div class="bg-primary border-b border-gray-600 w-full flex justify-between items-center h-full" style="height: 60px">
        <h1 class="text-4xl ml-5 text-white">
            {{ user?.name }}
        </h1>
        <h1 class="text-2xl ml-5 text-white/70">
            {{ currentChannel?.name }} | {{ currentGuild?.name }}
        </h1>
        <dropbox/>
    </div>
</template>

<script>
import Dropbox from './dropbox.vue';
import {mapState} from "vuex";

export default {
    components: {
        Dropbox,
    },
    computed: {
        ...mapState('auth', ['user']),
        ...mapState('guilds', ['currentGuild']),
        ...mapState('channel', ['currentChannel'])
    },
    methods: {
        toggleSideBar() {
            this.$store.commit('sidebar/toggle');
        },
    },
}
</script>
