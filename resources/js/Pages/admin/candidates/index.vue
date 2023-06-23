<script>
export default {
    layout: AppLayout,
};
</script>

<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import { array, object, string } from "vue-types";
import AppLayout from "@/layouts/apps.vue";
import VBreadcrumb from "@/components/VBreadCrumb/index.vue";

const props = defineProps({
    title: string(),
});

const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route("dashboard.index"),
    },
    {
        name: "Candidates",
        active: true,
        to: route("candidates.index"),
    },
];
</script>

<template>
    <Head :title="title" />
    <VBreadcrumb :routes="breadcrumb" />
</template>
