<template>
    <div>
        <button @click="toggleDropdown" id="dropdownHoverButton"
                class="text-white focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">
            <FontAwesomeIcon :icon="faGear()" size="2x"/>
        </button>
        <div v-show="isDropdownVisible" id="dropdownHover"
             class="z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 fixed mt-1 -right-0">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                aria-labelledby="dropdownHoverButton">
                <li v-for="item in items">
                    <button @click="item.action"
                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white w-full">{{
                            item.name
                        }}</button>
                </li>
            </ul>
        </div>

    </div>
</template>

<script>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faGear} from "@fortawesome/free-solid-svg-icons";

export default {
    components: {FontAwesomeIcon},
    data() {
        return {
            isDropdownVisible: false,
            items: [
                {name: 'Dashboard', action: '#'},
                {name: 'Log out', action: async () => await this.$store.dispatch('auth/logout')},
            ]
        };
    },
    methods: {
        faGear() {
            return faGear
        },
        toggleDropdown() {
            this.isDropdownVisible = !this.isDropdownVisible;
        },
    },
};
</script>
