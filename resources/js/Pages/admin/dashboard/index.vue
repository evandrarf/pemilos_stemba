<script>
export default {
    layout: AppLayout,
};
</script>
<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import { array, object, string } from "vue-types";
import AppLayout from "@/layouts/apps.vue";

const props = defineProps({
    filter: object(),
    query: array(),
    modules: array(),
    title: string(),
});
</script>

<template>
    <Head :title="props.title" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Admin</h1>
    </div>
    <p class="leading-8">
        Halaman Admin CMS Pemilihan Osis SMKN 7 Semarang merupakan halaman yang
        dirancang khusus untuk administrator atau pengelola pemilihan Organisasi
        Siswa Intra Sekolah (OSIS) di SMKN 7 Semarang. Halaman ini memberikan
        akses dan kontrol penuh kepada admin untuk mengelola seluruh aspek yang
        terkait dengan pemilihan OSIS, mulai dari konfigurasi, pengaturan,
        hingga pemantauan dan analisis.
    </p>
</template>
