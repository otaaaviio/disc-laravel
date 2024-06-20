<template>
    <confirm-modal :cancel-action="cancelAction" :delete-action="deleteAction" v-if="isOpen"/>
    <button @click="openModal" class="text-white/40 hover:text-white/60">
        <font-awesome-icon :icon="faTrash()"/>
    </button>
</template>

<script>
import ConfirmModal from "../utils/confirm-modal.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faTrash} from "@fortawesome/free-solid-svg-icons";

export default {
    components: {FontAwesomeIcon, ConfirmModal},
    props: {
        messageId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            isOpen: false
        }
    },
    methods: {
        faTrash() {
            return faTrash
        },
        openModal() {
            this.isOpen = true
        },
        async deleteAction() {
            this.isOpen = false
            await this.$store.dispatch('message/delete', this.messageId);
        },
        cancelAction() {
            this.isOpen = false
        }
    }
}
</script>
