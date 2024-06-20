<template>
    <div class="bg-primary border-b border-gray-600 w-full flex justify-between items-center h-full"
         style="height: 60px">
        <h1 class="text-4xl ml-5 text-white">
            {{ user?.name }}
        </h1>
        <h1 class="text-1xl ml-5 text-white/70">
            {{ currentChannel?.name }} | {{ currentGuild?.name }}
        </h1>
        <div class="flex items-center justify-center">
            <dropbox :items="menuItems" size="2x"/>
            <button class="mr-5 ml-5 text-white/50 hover:text-white/90" @click="logout">
                <FontAwesomeIcon :icon="faRightFromBracket()" size="2x"/>
            </button>
        </div>
    </div>
</template>

<script>
import Dropbox from './dropbox.vue';
import {mapState} from "vuex";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faRightFromBracket} from "@fortawesome/free-solid-svg-icons";

export default {
    data() {
        return {
            menuItems: [
                {
                    name: 'Register Guild', action: () => {
                    }
                },
                {
                    name: 'Delete Current Guild', action: () => {
                    }
                },
            ]
        }
    },
    components: {
        FontAwesomeIcon,
        Dropbox,
    },
    computed: {
        ...mapState('auth', ['user']),
        ...mapState('guilds', ['currentGuild']),
        ...mapState('channel', ['currentChannel'])
    },
    methods: {
        faRightFromBracket() {
            return faRightFromBracket
        },
        async logout() {
            await this.$store.dispatch('auth/logout')
        }
    },
}
</script>
