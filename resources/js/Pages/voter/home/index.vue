<script setup>
import VSearch from "@/components/src/icons/VSearch.vue";
import { ref, onMounted } from "vue";
import { string } from "vue-types";
import debounce from "@/composables/debounce";
import axios from "axios";
import VCandidateCard from "./CandidateCard.vue";
import VLoading from "@/components/VLoading/index.vue";
import VAlert from "../Alert.vue";
import { Head } from "@inertiajs/inertia-vue3";
import VPopup from "./Popup.vue";

const query = ref({});
const isLoading = ref(true);
const openAlertLogout = ref(false);
const showPopup = ref(false);

const props = defineProps({
    title: string(),
});

const getData = debounce(() => {
    axios
        .get(route("candidate-pairs.get-data"), { page: 1 })
        .then((res) => {
            query.value = res.data.data;
        })
        .catch((res) => {
            console.log(res);
        })
        .finally(() => (isLoading.value = false));
}, 500);

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

const handleOpenAlertLogout = () => {
    openAlertLogout.value = true;
};

const handleClosePopup = () => {
    showPopup.value = false;
    localStorage.setItem("showPopup", "false");
};

onMounted(() => {
    getData();
    showPopup.value = localStorage.getItem("showPopup") === "true";
});
</script>

<template>
    <Head :title="title" />
    <div
        class="w-screen min-h-screen h-max lg:h-screen bg-[#FAB18B] flex justify-center items-center font-vcr"
    >
        <div
            class="w-[90%] rounded-[30px] flex flex-col border-2 xl:min-h-0 lg:min-h-screen border-black lg:h-[90%] bg-[#E8E8E8]"
        >
            <div
                class="lg:flex grid grid-cols-2 gap-y-3 w-full justify-between border-b-2 border-black p-6"
            >
                <div
                    class="flex flex-row col-span-2 lg:flex-col row-span-1 justify-between"
                >
                    <!-- Red green Blue circle -->
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

                    <!-- Back circle -->
                    <div class="hidden lg:flex gap-2">
                        <div
                            class="border border-black rounded-sm p-1 flex justify-end items-center w-6 lg:w-8 h-6 lg:h-8"
                        >
                            <div
                                class="border-b-2 border-l-2 rotate-45 border-black w-3 lg:w-4 h-3 lg:h-4"
                            ></div>
                        </div>
                        <div
                            class="border border-black rounded-sm p-1 flex justify-start items-center w-6 lg:w-8 h-6 lg:h-8"
                        >
                            <div
                                class="border-t-2 border-r-2 rotate-45 border-black w-3 lg:w-4 h-3 lg:h-4"
                            ></div>
                        </div>
                        <div
                            class="border border-black rounded-sm p-1 flex flex-col gap-1 justify-center items-center w-6 lg:w-8 h-6 lg:h-8"
                        >
                            <div class="w-[90%] h-[2px] bg-black"></div>
                            <div class="w-[90%] h-[2px] bg-black"></div>
                            <div class="w-[90%] h-[2px] bg-black"></div>
                        </div>
                    </div>

                    <!-- Close button -->
                    <div class="flex lg:hidden gap-2">
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
                <h1
                    class="lg:text-[50px] text-center grid row-span-2 col-span-2 text-2xl"
                >
                    PILIH CALON KETUA OSISMU!
                </h1>
                <div
                    class="flex lg:flex-col col-span-2 justify-between items-end"
                >
                    <div class="hidden lg:flex gap-2">
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
                    <!-- Back circle -->
                    <div class="flex lg:hidden gap-2">
                        <div
                            class="border border-black rounded-sm p-1 flex justify-end items-center w-6 lg:w-8 h-6 lg:h-8"
                        >
                            <div
                                class="border-b-2 border-l-2 rotate-45 border-black w-3 lg:w-4 h-3 lg:h-4"
                            ></div>
                        </div>
                        <div
                            class="border border-black rounded-sm p-1 flex justify-start items-center w-6 lg:w-8 h-6 lg:h-8"
                        >
                            <div
                                class="border-t-2 border-r-2 rotate-45 border-black w-3 lg:w-4 h-3 lg:h-4"
                            ></div>
                        </div>
                        <div
                            class="border border-black rounded-sm p-1 flex flex-col gap-1 justify-center items-center w-6 lg:w-8 h-6 lg:h-8"
                        >
                            <div class="w-[90%] h-[2px] bg-black"></div>
                            <div class="w-[90%] h-[2px] bg-black"></div>
                            <div class="w-[90%] h-[2px] bg-black"></div>
                        </div>
                    </div>
                    <div>
                        <div
                            class="flex justify-end items-center px-2 text w-full lg:min-w-[150px] border border-black rounded-md"
                        >
                            <span class="mr-2">search</span>
                            <VSearch size="sm" />
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="flex flex-col lg:flex-row mx-auto items-center h-full w-full"
                :class="query.length < 3 ? 'justify-center' : 'justify-between'"
            >
                <h2 v-if="query.length === 0" class="text-3xl">
                    Belum ada calon kandidat
                </h2>
                <VLoading v-else-if="isLoading" />
                <VCandidateCard
                    v-for="(data, index) in query"
                    :key="index"
                    :data="data"
                    :index="index"
                    :length="query.length"
                    v-else
                />
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
    <VPopup v-if="showPopup" @close="handleClosePopup" />
</template>
