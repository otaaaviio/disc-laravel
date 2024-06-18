<template>
    <form ref="form" @submit.prevent="login">
        <div class="flex flex-col gap-4">
            <input
                v-model="formData.email"
                type="email"
                placeholder="Email"
                required
                class="p-3 rounded-lg text-black">
            <input
                v-model="formData.password"
                type="password"
                placeholder="Password"
                minlength="6"
                class="p-3 rounded-lg text-black">
            <div class="flex">
                <input type="checkbox" id="remember-me" v-model="formData.remember"/>
                <label for="remember-me" class="inline-flex ml-2">Remember me</label>
            </div>
            <button
                @click="submitLogin"
                class="p-3 bg-blue-500 rounded-lg w-1/2 mx-auto"
            >
                Login
            </button>
        </div>
    </form>
</template>

<script>
export default {
    name: 'login-form',
    data() {
        return {
            formData: {
                email: '',
                password: '',
                remember: false
            },
        }
    },
    methods: {
        async submitLogin() {
            const valid = this.$refs.form.checkValidity();
            if (!valid) return;

            await this.$store.dispatch('auth/login', this.formData);
        },
    },
    mounted() {
        if (process.env.NODE_ENV === 'development') {
            this.formData = {
                email: 'adm@admin.com',
                password: 'password',
            };
        }
    }
}
</script>
