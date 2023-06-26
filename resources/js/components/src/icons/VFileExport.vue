<script setup>
import { bool, string } from "vue-types";

const props = defineProps({
    isActive: bool().def(false),
    color: string().def("text-white"),
});
</script>

<template>
    <font-awesome-icon
        icon="fa-solid fa-file-export"
        :class="{ '!text-blue-500': isActive, [color]: !isActive }"
    />
</template>
