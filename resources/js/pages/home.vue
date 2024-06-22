<template>
    <div class="flex flex-col min-h-screen items-center text-white">
        <background/>
        <div class="relative flex-grow w-full max-w-2xl px-6 lg:max-w-7xl ">
            <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                <div class="flex lg:justify-center lg:col-start-2">
                    <img src="../assets/images/disc-icon.svg" alt="disc-icon">
                </div>
                <nav class="-mx-3 flex flex-1 justify-end">
                    <div v-if="isLogged">
                        <button class="p-4 rounded-lg" @click="redirect('/dashboard')">dashboard</button>
                    </div>
                    <div v-if="!isLogged">
                        <button class="p-4 rounded-lg" @click="redirect('/login')">login</button>
                    </div>
                </nav>
            </header>
            <main class="mt-6 flex justify-center">
                <div class="flex flex-col gap-6 max-w-2xl">
                    <h1 class="text-4xl">Discord Clone</h1>
                    <h3>
                        A simple Discord clone built with VueJS, Laravel, Reverb and PostgreSQL.<br>
                        The objective of this project is apply WebSocket with Laravel Reverb,
                        implementing a real-time chat application. Let's chat! 🚀
                    </h3>
                </div>
            </main>
            <app-footer/>
        </div>
    </div>
</template>

<script>
import {defineComponent} from "vue";
import AppFooter from "../components/app-footer.vue";
import Background from "../components/background.vue";
import {mapGetters} from "vuex";

export default defineComponent({
    components: {Background, AppFooter},
    computed: {
        ...mapGetters('auth', ['isLogged']),
    },
    methods: {
        redirect(route) {
            this.$router.push(route);
        },
    },
})
</script>
