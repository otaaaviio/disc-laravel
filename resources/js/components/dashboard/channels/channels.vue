<template>
    <div class="flex flex-col h-full max-h-[calc(100vh-60px)]">
        <channel-manager :cancel-action="() => isOpenModal = false" v-if="isOpenModal"/>
        <button
            @click="isOpenModal = true"
            v-if="getCurrentUserIsAdmin"
            class="py-4 hover:bg-gray-100/5 text-green-600">
            Register New Channel
        </button>
        <div class="flex flex-col-reverse overflow-y-auto hide-scrollbar">
            <card-channel v-for="channel in channels" :channel="channel"/>
        </div>
    </div>
</template>

<script>
import CardChannel from "./card-channel.vue";
import ChannelManager from "../../guild/channel-manager.vue";
import {mapGetters} from "vuex";

export default {
    components: {CardChannel, ChannelManager},
    props: {
        channels: {
            type: Array,
            default: []
        }
    },
    computed: {
        ...mapGetters('guilds', ['getCurrentUserIsAdmin'])
    },
    data() {
        return {
            isOpenModal: false
        }
    }
}
</script>
