<script>
import authLayout from "@/layouts/voter-auth.vue";
import { string } from "vue-types";

export default {
    layout: authLayout,
};
</script>

<script setup>
import LoginForm from "./LoginForm.vue";
import { ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { string } from "vue-types";

const isStudent = ref(true);

const props = defineProps({
    title: string(),
});

const changeIsStudent = (value) => {
    isStudent.value = value;
};
</script>

<template>
    <Head :title="title" />
    <LoginForm :isStudent="isStudent" @changeIsStudent="changeIsStudent" />
</template>
