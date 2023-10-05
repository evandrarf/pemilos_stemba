<script setup>
import { ref, computed, watch } from "vue";
import axios from "axios";
import VXmark from "@/components/src/icons/VXmark.vue";
import { usePage } from "@inertiajs/inertia-vue3";

const btn = "px-6 py-2 text-md md:text-xl rounded-md border-2 border-black";

const user = computed(() => usePage().props.value.user);
</script>
<template>
    <div
        class="fixed z-30 inset-0 bg-gray-600 opacity-80"
        @click="$emit('close')"
    ></div>
    <div class="fixed z-40 inset-0 flex justify-center items-center font-vcr">
        <div
            class="md:w-2/5 w-[80%] min-h-[33%] flex flex-col overflow-hidden rounded-[36px] bg-[#E8E8E8] border-2 border-black justify-between pb-6"
            @focusout="$emit('close')"
        >
            <div class="bg-[#A8C1D1] px-14 py-2 text-xl md:text-2xl">
                <h2 class="text-center">Selamat Datang</h2>
            </div>
            <div class="px-4 py-4">
                <p
                    class="text-center text-base md:text-lg"
                    v-if="user.type === 'student'"
                >
                    Selamat datang {{ user.name }} dari kelas {{ user.class }}.
                    Silahkan pilih pasangan calon ketua dan wakil ketua osis.
                    Gunakan hak pilih anda dengan bijak.
                </p>
                <p class="text-center text-base md:text-lg" v-else>
                    Selamat datang {{ user.name }}. Silahkan pilih pasangan
                    calon ketua dan wakil ketua osis. Gunakan hak pilih anda
                    dengan bijak.
                </p>
            </div>
            <div class="flex justify-center gap-8 items-center h-full">
                <button
                    :class="btn"
                    class="bg-[#9CBA8F]"
                    @click="$emit('close')"
                >
                    Dimengerti
                </button>
            </div>
        </div>
    </div>
</template>
