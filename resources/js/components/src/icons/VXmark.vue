<script setup>
import { string } from "vue-types";

const props = defineProps({
    class: string(),
});
</script>

<template>
    <font-awesome-icon icon="fa-solid fa-xmark" :class="class" />
</template>
