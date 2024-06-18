<template>
    <div class="flex flex-col min-h-screen items-center text-white">
        <background/>
        <div class="relative flex-grow w-full max-w-2xl px-6 lg:max-w-7xl ">
            <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                <div class="flex lg:justify-center lg:col-start-2">
                    <a href="/">
                        <img src="../assets/disc-icon.svg" alt="disc-icon">
                    </a>
                </div>
                <nav class="-mx-3 flex flex-1 justify-end">
                    <button @click="handleAuth" class="pa-3 hover:">{{ option }}</button>
                </nav>
            </header>
            <main class="mt-6 flex justify-center">
                <div class="flex flex-col gap-6 max-w-2xl">
                    <login-form v-if="login"/>
                    <register-form v-if="!login"/>
                </div>
            </main>
            <app-footer/>
        </div>
    </div>
</template>

<script>
import AppFooter from "../components/app-footer.vue";
import Background from "../components/background.vue";
import LoginForm from "../components/login-form.vue";
import RegisterForm from "../components/register-form.vue";

export default {
    components: {RegisterForm, LoginForm, Background, AppFooter},
    data() {
        return {
            login: false,
            option: 'login'
        }
    },
    methods: {
        handleAuth() {
            this.option = this.login ? 'login' : 'register';
            this.login = !this.login;
        },
    },
}
</script>
