<script setup>
import { string } from "vue-types";

const props = defineProps({
    size: string().def("default"),
});
</script>

<template>
    <font-awesome-icon icon="fa-solid fa-magnifying-glass" :size="size" />
</template>
