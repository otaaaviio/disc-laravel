<template>
    <div class="flex items-start my-1 ml-1 mr-1 pl-2 border-b border-white/20">
        <div class="flex flex-col w-full max-w-[800px] leading-1.5">
            <div class="flex items-center space-x-2 rtl:space-x-reverse justify-between pr-3">
                <span class="text-sm font-semibold text-white/80">{{ user.name }}</span>
                <span class="text-sm font-normal text-green-500">{{ user.online ? 'in chat' : ''}}</span>
            </div>
            <p class="text-xs font-normal py-2 text-white/60">{{ user.role }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
            default: () => ({
                name: 'nome',
                role: 'admin',
                online: true,
            })
        }
    },
}
</script>
