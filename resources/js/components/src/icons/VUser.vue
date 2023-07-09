<script setup>
import { string } from "vue-types";

const props = defineProps({
    color: string().def("text-slate-400"),
    size: string().def(""),
});
</script>

<template>
    <font-awesome-icon
        icon="fa-solid fa-user"
        class=""
        :class="{ '!text-blue-500': isActive, [color]: true }"
        :size="size"
    />
</template>
