<template>
    <div class="relative inline-block">
        <button @click="toggleDropdown" id="dropdownHoverButton"
                class="text-white px-5 py-2.5 text-center items-center"
                type="button">
            <FontAwesomeIcon :icon="faEllipsisVertical()" :size="size"/>
        </button>
        <div v-show="isDropdownVisible" id="dropdownHover"
             class="absolute z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 mt-2 right-0">
            <ul class="py-2 text-sm text-gray-700"
                aria-labelledby="dropdownHoverButton">
                <li v-for="item in items">
                    <button @click="item.action" class="block px-4 py-2 hover:bg-gray-100 w-full">{{ item.name }}</button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faEllipsisVertical} from "@fortawesome/free-solid-svg-icons";

export default {
    components: {FontAwesomeIcon},
    props: {
        items: {
            type: Array,
            required: true
        },
        size: {
            required: true
        }
    },
    mounted() {
        document.addEventListener('click', this.closeDropdownIfClickedOutside);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.closeDropdownIfClickedOutside);
    },
    data() {
        return {
            isDropdownVisible: false,
        };
    },
    methods: {
        closeDropdownIfClickedOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.isDropdownVisible = false;
            }
        },
        faEllipsisVertical() {
            return faEllipsisVertical
        },
        toggleDropdown() {
            this.isDropdownVisible = !this.isDropdownVisible;
        },
    },
};
</script>
