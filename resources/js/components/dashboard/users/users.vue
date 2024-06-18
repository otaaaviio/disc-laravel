<template>
    <div class="flex flex-col h-full max-h-[calc(100vh-60px)]">
        <div class="flex flex-col-reverse overflow-y-auto hide-scrollbar">
            <card-user v-for="user in users" :user="user"/>
        </div>
    </div>
</template>

<script>
import CardUser from "./card-user.vue";

export default {
    components: {CardUser},
    props: {
        users: {
            type: Array,
            default: []
        }
    },
}
</script>
