<template>
    <div class="bg-primary border-b border-gray-600 w-full flex justify-between items-center h-full" style="height: 60px">
        <h1 class="text-4xl ml-5 text-white">
            {{ user?.name }}
        </h1>
        <h1 class="text-1xl ml-5 text-white/70">
            {{ currentChannel?.name }} | {{ currentGuild?.name }}
        </h1>
        <dropbox :items="menuItems" size="2x"/>
    </div>
</template>

<script>
import Dropbox from './dropbox.vue';
import {mapState} from "vuex";

export default {
    data() {
        return {
            menuItems: [
                {name: 'Dashboard', action: () => {}},
                {name: 'Log out', action: async () => await this.$store.dispatch('auth/logout')},
            ]
        }
    },
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
