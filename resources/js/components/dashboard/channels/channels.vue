<template>
    <div class="flex flex-col h-full max-h-[calc(100vh-60px)]">
        <div class="flex flex-col-reverse overflow-y-auto hide-scrollbar">
            <card-channel v-for="channel in channels" :channel="channel"/>
        </div>
    </div>
</template>

<script>
import CardChannel from "./card-channel.vue";

export default {
    components: {CardChannel},
    props: {
        channels: {
            type: Array,
            default: []
        }
    },
}
</script>
