<template>
    <form ref="form" @submit.prevent="login">
        <div class="flex flex-col gap-4">
            <input
                v-model="formData.name"
                type="text"
                placeholder="Name"
                required
                class="p-3 rounded-lg text-black">
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
                required
                class="p-3 rounded-lg text-black">
            <input
                v-model="formData.password_confirmation"
                type="password"
                placeholder="Confirm Password"
                minlength="6"
                required
                class="p-3 rounded-lg text-black">
            <button
                @click="register"
                class="p-3 bg-blue-500 rounded-lg w-1/2 mx-auto"
            >
                Register
            </button>
        </div>
    </form>
</template>

<script>
import {toast} from "vue3-toastify";

export default {
    name: 'register-form',
    data() {
        return {
            formData: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
        }
    },
    methods: {
        async register() {
            const valid = this.$refs.form.checkValidity();
            if (!valid) return;

            if (this.formData.password !== this.formData.password_confirmation) {
                toast.error('Password and Confirm Password must be the same');
                return;
            }

            await this.$store.dispatch('auth/register', this.formData);
        },
    },
}
</script>
