<template>
    <div class="flex flex-row w-full">
        <side-bar/>
        <div class="flex flex-col flex-grow">
            <top-bar/>
            <div class="main-content flex-grow">
                <slot/>
            </div>
        </div>
    </div>
</template>

<script>
import TopBar from "../components/layout/top-bar.vue";
import SideBar from "../components/layout/side-bar.vue";

export default {
    components: {
        TopBar,
        SideBar
    }
};
</script>
