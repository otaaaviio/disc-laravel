<template>
    <div
        @click="cancelAction"
        class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.8)] overflow-auto font-[sans-serif]">
        <div @click.stop class="w-full max-w-md bg-bars shadow-lg rounded-lg p-6 relative">
            <button @click="cancelAction" class="float-right">
                <font-awesome-icon :icon="faX()" size="1x"/>
            </button>

            <div class="my-8 text-center">
                    <font-awesome-icon class="text-red-500/70" :icon="faTrash()" size="2x"/>
                <h4 class="text-white/80 text-lg font-semibold mt-4">Delete Action</h4>
                <p class="text-sm text-white/80 mt-4">
                    Are you sure you want to delete this item? This action cannot be undone.
                </p>
            </div>
            <div class="flex flex-col space-y-2">
                <button type="button"
                        @click="deleteAction"
                        class="px-4 py-2 rounded-lg text-white text-sm tracking-wide bg-red-500 hover:bg-red-600 active:bg-red-500">
                    Delete
                </button>
                <button type="button"
                        @click="cancelAction"
                        class="px-4 py-2 rounded-lg text-gray-800 text-sm tracking-wide bg-gray-200 hover:bg-gray-300 active:bg-gray-200">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faTrash, faX} from "@fortawesome/free-solid-svg-icons";

export default {
    props: {
        cancelAction: {
            type: Function,
            required: true
        },
        deleteAction: {
            type: Function,
            required: true
        }
    },
    components: {FontAwesomeIcon},
    methods: {
        faX() {
            return faX
        },
        faTrash() {
            return faTrash
        },
    },
}
</script>
