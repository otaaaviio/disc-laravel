<template>
    <div class="flex items-start gap-2.5 my-1 ml-1">
        <img class="rounded-full" :src="formatIcon(message.user.name)" alt="Jese image">
        <div class="flex flex-col w-full max-w-[800px] leading-1.5">
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ message.user.name }}</span>
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ message?.sent_at }}</span>
            </div>
            <p class="text-sm font-normal py-2 text-gray-900">{{ message.content }}</p>
        </div>
    </div>
</template>

<script>
export default {
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
    methods: {
        formatIcon(name) {
            if(!name) return 'https://via.placeholder.com/35x35.png/000?text=';

            const first = name.charAt(0).toUpperCase();
            return 'https://via.placeholder.com/35x35.png/000?text=' + first;
        }
    }
}
</script>

