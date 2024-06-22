<template>
    <div
        class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.8)] overflow-auto font-[sans-serif]">
        <div @click.stop class="w-full max-w-md bg-bars shadow-lg rounded-lg p-6 relative">
            <button class="float-right" @click="cancelAction">
                <font-awesome-icon :icon="faX()" size="1x"/>
            </button>
            <div class="my-8 text-center">
                <h4 class="text-white/80 text-lg font-semibold">Entry a Guild</h4>
                <form ref="form" @submit.prevent="submit">
                    <div class="flex flex-col gap-4 mt-5">
                        <input
                            v-model="code"
                            type="text"
                            placeholder="Code"
                            required
                            class="p-3 rounded-lg text-black">
                        <button
                            type="submit"
                            class="p-3 bg-blue-500 rounded-lg w-1/2 mx-auto"
                        >
                            Confirm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faX} from "@fortawesome/free-solid-svg-icons";

export default {
    data() {
        return {
            code: ''
        }
    },
    props: {
        cancelAction: {
            type: Function,
            required: true
        },
    },
    components: {FontAwesomeIcon},
    methods: {
        faX() {
            return faX
        },
        async submit() {
            const valid = this.$refs.form.checkValidity();
            if (!valid) return;

            this.cancelAction();

            await this.$store.dispatch('guilds/joinViaCode', this.code);
        },
    },
}
</script>
