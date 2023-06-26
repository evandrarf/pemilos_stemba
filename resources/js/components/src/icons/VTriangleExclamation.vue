<script setup>
import { bool, string } from "vue-types";

const props = defineProps({
    isActive: bool().def(false),
    color: string().def("text-white"),
    disabled: bool().def(false),
});
</script>

<template>
    <font-awesome-icon
        icon="fa-solid fa-triangle-exclamation"
        :class="{
            '!text-blue-500': isActive,
            'text-slate-400': disabled,
            [color]: !isActive,
        }"
    />
</template>
