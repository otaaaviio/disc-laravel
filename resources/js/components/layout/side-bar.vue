<template>
    <div class="sidebar bg-side-bar">
        <div class="disc-icon mt-2">
            <img src="../../assets/images/disc-icon.svg" alt="disc-icon"/>
        </div>
        <hr class="border-t-2 border-white/20 my-0.5 w-3/4 "/>
        <div class="icon-container" v-for="item in guilds" :key="item.id">
            <button class="icon-button"
                    @click="setGuild(item.id)"
                    :style="{ backgroundImage: `url(${item.icon_url})` }"
            />
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    computed: {
        ...mapState('guilds', ['guilds']),
    },
    mounted() {
        this.$store.dispatch('guilds/index');
    },
    methods: {
        async setGuild(id) {
            if(this.$store.state.guilds.currentGuild?.id !== id)
                await this.$store.dispatch('guilds/show', id);
        }
    }
}
</script>
