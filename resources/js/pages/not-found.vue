<template>
    <div class="flex flex-col min-h-screen items-center text-white">
        <background/>
        <div class="relative flex-grow w-full max-w-2xl px-6 lg:max-w-7xl ">
            <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                <div class="flex lg:justify-center lg:col-start-2">
                    <a href="/">
                        <img src="../assets/images/disc-icon.svg" alt="disc-icon">
                    </a>
                </div>
            </header>
            <main class="mt-6 flex justify-center">
                <div class="flex flex-col gap-6 max-w-2xl">
                    <div class="flex items-baseline">
                        <h1 class="text-8xl mr-2">404</h1>
                        Page Not Found
                    </div>
                    <p class="text-1xl flex text-white/50">
                        The page you are looking for does not exist.<br> How you got here is a mystery.
                    </p>
                    <div class="w-full flex justify-center">
                    <button
                        @click="$router.push('/dashboard')"
                        class="text-white/50 p-5 bg-bars rounded-lg hover:text-white/80">
                        Go back Dashboard
                    </button>
                    </div>
                </div>
            </main>
            <app-footer/>
        </div>
    </div>
</template>

<script>
import AppFooter from "../components/app-footer.vue";
import Background from "../components/background.vue";

export default {
    components: {Background, AppFooter},
}
</script>
