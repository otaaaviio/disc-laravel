<template>
    <button
        @click="submitChannel(channel)"
        class="flex items-start gap-2.5 py-3 pl-1 border-t border-b border-white/10 hover:bg-gray-600 transition">
        <span class="text-xl font-semibold limited-text"># {{ channel.name }}</span>
    </button>
</template>

<script>
export default {
    props: {
        channel: {
            type: Object,
            default: {}
        }
    },
    methods: {
        async submitChannel(channel) {
            await this.$store.dispatch('channel/join', channel);
        }
    }
}
</script>

<style>
.limited-text {
    display: block;
    white-space: nowrap;
    overflow-x: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}
</style>
