<template>
    <button @click="handleClick" class="text-white/40 hover:text-white/60">
        <font-awesome-icon :icon="getIcon()" />
    </button>
</template>

<script>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faEye, faEyeSlash} from "@fortawesome/free-solid-svg-icons";

export default {
    props: {
        handleClick: {
            type: Function,
            required: true
        },
        isBlur: {
            type: Boolean,
            required: true
        }
    },
    methods: {
        getIcon() {
            if(this.isBlur)
                return faEyeSlash

            return faEye
        }
    },
    components: {FontAwesomeIcon},
}
</script>
