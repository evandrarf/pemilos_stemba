<script setup>
import { bool, string } from "vue-types";

const props = defineProps({
    isActive: bool().def(false),
    color: string().def("text-slate-400"),
});
</script>

<template>
    <font-awesome-icon
        icon="fa-solid fa-key"
        :class="{ '!text-blue-500': isActive, [color]: !isActive }"
    />
</template>
