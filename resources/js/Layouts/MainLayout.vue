<script>
import {Link} from "@inertiajs/vue3";
import Navigation from "@/Components/Navigation.vue";

export default {
    name: "MainLayout",
    components: {
        Link,
        Navigation
    }
}
</script>

<template>
    <div class="container mx-auto">
        <Navigation></Navigation>
        <slot/>
    </div>
</template>

<style scoped>

</style>
