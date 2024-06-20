<template>
    <div class="flex items-start gap-2.5 my-1 ml-1">
        <img class="rounded-full" :src="formatIcon(message.user.name)" alt="Jese image">
        <div class="flex flex-col w-full leading-1.5">
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <span class="text-sm font-semibold text-white/60">{{ message.user.name }}</span>
                <span class="text-sm font-normal text-white/50">{{ message?.sent_at }}</span>
            </div>
            <p class="text-sm font-normal py-2 text-white/40">{{ message.content }}</p>
        </div>
        <div class="mr-5 flex items-center justify-center h-full" v-if="verifyOwnerUser">
            <button class="p-3">
                <font-awesome-icon :icon="faEllipsisVertical()" class="text-white/80"/>
            </button>
        </div>
    </div>
</template>

<script>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faEllipsisVertical} from "@fortawesome/free-solid-svg-icons";

export default {
    components: {FontAwesomeIcon},
    props: {
        message: {
            type: Object,
            default: () => ({
                user_name: 'nome',
                user_avatar: 'avatar',
                content: 'content',
                sent_at: 'sent_at'
            })
        }
    },
    computed: {
        verifyOwnerUser() {
            return this.$store.getters['auth/user'].user.id === this.message.user.id;
        }
    },
    methods: {
        faEllipsisVertical() {
            return faEllipsisVertical
        },
        formatIcon(name) {
            if(!name) return 'https://via.placeholder.com/35x35.png/000?text=';

            const first = name.charAt(0).toUpperCase();
            return 'https://via.placeholder.com/35x35.png/000?text=' + first;
        }
    }
}
</script>

