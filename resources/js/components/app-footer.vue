<template>
    <footer class="py-16 text-center text-sm absolute bottom-0 w-full">
        &copy; {{ (new Date()).getFullYear() }} <span class="d-none d-sm-inline-block">Discord</span>
        — MIT License
    </footer>
</template>

<script>
export default {
    name: 'AppFooter',
}
</script>
