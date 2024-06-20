<template>
    <confirm-modal :delete-action="confirmDelGuild" :cancel-action="() => isOpenModel = false" v-if="isOpenModel"/>
    <modal-manager :cancel-action="() => isOpenCreateModal = false" v-if="isOpenCreateModal"/>
    <entry-via-code :cancel-action="() => isOpenEntryModal = false" v-if="isOpenEntryModal"/>
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
import {mapGetters, mapState} from "vuex";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faRightFromBracket} from "@fortawesome/free-solid-svg-icons";
import ConfirmModal from "../utils/confirm-modal.vue";
import ModalManager from "../guild/guild-manager.vue";
import EntryViaCode from "../guild/entry-via-code.vue";

export default {
    data() {
        return {
            isOpenModel: false,
            isOpenCreateModal: false,
            isOpenEntryModal: false,
        }
    },
    components: {
        ModalManager,
        ConfirmModal,
        FontAwesomeIcon,
        Dropbox,
        EntryViaCode
    },
    computed: {
        ...mapState('auth', ['user']),
        ...mapState('guilds', ['currentGuild']),
        ...mapState('channel', ['currentChannel']),
        ...mapGetters('guilds', ['getCurrentUserIsAdmin']),
        menuItems() {
            return [
                {name: 'Entry a new Guild', action: () => this.isOpenEntryModal = true, disabled: false},
                {name: 'Register Guild', action: () => this.isOpenCreateModal = true, disabled: false},
                {
                    name: 'Delete Current Guild',
                    action: () => this.isOpenModel = true,
                    disabled: !this.getCurrentUserIsAdmin
                },
            ]
        }
    },
    methods: {
        faRightFromBracket() {
            return faRightFromBracket
        },
        async logout() {
            await this.$store.dispatch('auth/logout')
        },
        async confirmDelGuild() {
            this.isOpenModel = false;
            await this.$store.dispatch('guilds/delete', this.currentGuild.id);
        }
    },
}
</script>
