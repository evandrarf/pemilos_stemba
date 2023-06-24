<script setup>
import { string } from "vue-types";

const props = defineProps({
    color: string().def("primary"),
});
</script>

<template>
    <font-awesome-icon
        icon="fa-solid fa-user"
        class="w-5 h-5 text-slate-400"
        :class="isActive && '!text-blue-500'"
    />
</template>
