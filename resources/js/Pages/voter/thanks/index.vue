<script setup>
import { string } from "vue-types";
import { Head } from "@inertiajs/inertia-vue3";
import { onMounted, ref } from "vue";
import debounce from "@/composables/debounce";
import axios from "axios";
import VAlert from "../Alert.vue";

const props = defineProps({
    title: string(),
});

const openAlertLogout = ref(false);

const handleOpenAlertLogout = () => {
    openAlertLogout.value = true;
};

const logout = () => {
    axios
        .post(route("user-voter.logout"))
        .then(() => {
            localStorage.removeItem("showPopup");
            window.location.reload();
        })
        .catch((res) => {
            console.log(res);
        });
};

const handleCloseAlertLogout = () => {
    openAlertLogout.value = false;
};

const logoutAction = debounce(() => {
    logout();
}, 5000);

onMounted(() => {
    logoutAction();
});
</script>

<template>
    <Head :title="title" />
    <div
        class="h-screen w-screen bg-[#B08BBB] font-vcr flex justify-center items-center"
    >
        <div
            class="w-4/5 rounded-[30px] flex flex-col border-2 border-black h-[80%] lg:h-3/5 bg-[#E8E8E8] drop-shadow-[8px_8px_0_rgba(0,0,0,0.5)]"
        >
            <div
                class="lg:flex grid grid-cols-2 justify-between border-b-2 border-black p-6"
            >
                <div class="flex flex-col row-start-1 justify-between">
                    <div class="flex gap-2">
                        <div
                            class="w-4 h-4 lg:w-6 lg:h-6 rounded-full bg-blue-300"
                        ></div>
                        <div
                            class="w-4 h-4 lg:w-6 lg:h-6 rounded-full bg-[#C76E7A]"
                        ></div>
                        <div
                            class="w-4 h-4 lg:w-6 lg:h-6 rounded-full bg-[#9CBA8F]"
                        ></div>
                    </div>
                </div>
                <h4
                    class="text-xl row-start-2 col-span-2 text-center mt-6 lg:mt-0"
                >
                    pemilos_2023/2024
                </h4>
                <div
                    class="flex flex-col row-start-1 justify-between items-end"
                >
                    <div class="flex gap-2">
                        <div
                            class="w-6 lg:w-8 h-6 lg:h-8 border border-black rounded flex justify-center py-1.5 items-end"
                        >
                            <div class="w-4/5 bg-black h-0.5"></div>
                        </div>
                        <div
                            class="w-6 lg:w-8 h-6 lg:h-8 border border-black rounded relative flex justify-center items-center"
                        >
                            <div
                                class="border-2 border-black w-[60%] h-[60%] z-10 absolute left-1 bottom-1 bg-[#E8E8E8]"
                            ></div>
                            <div
                                class="border-2 border-black w-[60%] h-[60%] absolute right-1 top-1"
                            ></div>
                        </div>
                        <button
                            class="w-6 lg:w-8 h-6 lg:h-8 border bg-[#C76E7A] border-black rounded relative flex justify-center items-center"
                            @click="handleOpenAlertLogout"
                        >
                            <div
                                class="w-4/5 h-0.5 bg-black absolute origin-center -rotate-45"
                            ></div>
                            <div
                                class="w-4/5 h-0.5 bg-black absolute origin-center rotate-45"
                            ></div>
                        </button>
                    </div>
                </div>
            </div>
            <div
                class="flex flex-col justify-center items-center py-4 h-2/3 text-center w-full"
            >
                <h1 class="text-2xl lg:text-[90px]">TERIMA KASIH</h1>
                <h3 class="text-lg mt-4 lg:mt-0 lg:text-[30px]">
                    Jawaban Anda sudah tersimpan
                </h3>
            </div>
        </div>
    </div>
    <VAlert
        v-if="openAlertLogout"
        @close="handleCloseAlertLogout"
        @submit="logout"
        title="Apakah anda yakin ingin logout?"
        submitButton="YA"
        cancelButton="TIDAK"
    />
</template>
