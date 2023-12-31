<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { ref, computed } from "vue";
import { object, string } from "vue-types";
import VAlert from "../Alert.vue";
import axios from "axios";
import VNotification from "@/components/VNotification/Index.vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { notify } from "notiwind";
import { Head } from "@inertiajs/inertia-vue3";

const openAlertLogout = ref(false);
const openAlertVote = ref(false);

const user = computed(() => usePage().props.value.user);

const props = defineProps({
    additional: object().def({}),
    title: string(),
});

const { data } = props.additional.candidate_detail;

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

const handleOpenAlertVote = () => {
    openAlertVote.value = true;
};

const vote = () => {
    axios
        .post(route("user-voter.vote"), {
            candidate_pair_id: data.id,
            voter_id: user.value.id,
        })
        .then((res) => {
            window.location.reload();
        })
        .catch((res) => {
            notify(
                {
                    type: "error",
                    group: "top",
                    text: res.response.data.message,
                },
                2500
            );
        });
};

const handleCloseAlertVote = () => {
    openAlertVote.value = false;
};

const handleCloseAlertLogout = () => {
    openAlertLogout.value = false;
};

const handleOpenAlertLogout = () => {
    openAlertLogout.value = true;
};
</script>

<template>
    <VNotification></VNotification>
    <Head :title="title" />

    <div class="w-screen h-max lg:h-screen bg-[#FAB18B] p-6 lg:p-12 font-vcr">
        <div class="w-full h-full flex">
            <div
                class="w-1/3 z-10 h-full hidden lg:flex flex-col items-center justify-between"
            >
                <div
                    class="flex flex-col w-full h-2/3 text-white drop-shadow-[6px_6px_0_rgba(0,0,0,0.5)]"
                >
                    <div
                        class="p-4 flex justify-between items-center bg-[#7F7F7F]"
                    >
                        <span>paslon_{{ data.number }}.jpeg</span>
                        <span
                            class="flex justify-center w-6 h-6 items-center relative"
                        >
                            <div
                                class="absolute w-4/5 h-0.5 origin-center rotate-45 bg-white"
                            ></div>
                            <div
                                class="absolute w-4/5 h-0.5 origin-center -rotate-45 bg-white"
                            ></div>
                        </span>
                    </div>
                    <div
                        class="w-full h-2/3 bg-[#979797] flex justify-center items-center overflow-hidden pt-2"
                    >
                        <img
                            :src="data.image"
                            class="object-contain h-full"
                            :alt="'paslon_' + data.number"
                        />
                    </div>
                    <div
                        class="bg-[#979797] relative h-12 flex justify-center text-black"
                    >
                        <div
                            class="flex mt-4 bg-[#E8E8E8] max-h-28 absolute flex-col overflow-hidden items-center w-[90%]"
                        >
                            <div class="self-start flex text-sm">
                                <span
                                    class="bg-[#A8C1D1] py-1 pl-3 pr-16 relative"
                                    >Nama Calon

                                    <span
                                        class="bg-[#A8C1D1] w-12 h-12 rotate-45 absolute -right-6 -top-7"
                                    ></span>
                                </span>
                            </div>
                            <div
                                class="flex flex-col h-full text-sm mb-4 overflow-y-auto w-full px-6 py-2"
                            >
                                <span
                                    >1. {{ data.chairman.name }} ({{
                                        data.chairman.class
                                    }})</span
                                >
                                <span
                                    >2. {{ data.vice_chairman.name }} ({{
                                        data.vice_chairman.class
                                    }})</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="w-full h-1/4 bg-[#E8E8E8] overflow-hidden rounded-3xl border-2 border-black flex flex-col drop-shadow-[6px_6px_0_rgba(0,0,0,0.5)]"
                >
                    <div
                        class="w-full bg-[#A8C1D1] flex justify-center items-center text-2xl py-2 border-b-2 border-black px-3"
                    >
                        <h2>Pilih Kandidat?</h2>
                        <span
                            class="flex justify-center w-6 h-6 items-center relative -right-24 p-1 border border-black rounded"
                        >
                            <div
                                class="absolute w-full h-0.5 origin-center rotate-45 bg-black"
                            ></div>
                            <div
                                class="absolute w-full h-0.5 origin-center -rotate-45 bg-black"
                            ></div>
                        </span>
                    </div>
                    <div
                        class="flex justify-center gap-4 items-center h-full text-3xl"
                    >
                        <button
                            @click="handleOpenAlertVote"
                            class="w-40 bg-[#9CBA8F] rounded-xl py-1 border-2 border-black"
                        >
                            Pilih
                        </button>
                        <Link :href="route('user-voter.index')">
                            <button
                                class="w-40 bg-[#C76E7A] rounded-xl py-1 border-2 border-black"
                            >
                                Kembali
                            </button>
                        </Link>
                    </div>
                </div>
            </div>
            <div class="lg:w-2/3 w-full relative">
                <div
                    class="lg:w-[110%] w-full relative lg:absolute lg:-top-3 lg:-left-24 rounded-[30px] flex flex-col border-2 pb-12 lg:pb-0 border-black h-max lg:h-full bg-[#E8E8E8] drop-shadow-[6px_6px_0_rgba(0,0,0,0.5)]"
                >
                    <div
                        class="lg:flex grid grid-cols-2 justify-between border-b-2 border-black p-6"
                    >
                        <!-- Red green blue button -->
                        <div class="flex row-start-1 gap-2">
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
                        <h4 class="text-xl row-start-2 text-center col-span-2">
                            Paslon_{{ data.number }}.txt
                        </h4>
                        <!-- Close button -->
                        <div class="flex row-start-1 gap-2 justify-self-end">
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
                    <div class="w-full lg:hidden flex flex-col items-center">
                        <div
                            class="flex flex-col w-[90%] -mt-3 h-[200px] text-white drop-shadow-[6px_6px_0_rgba(0,0,0,0.5)]"
                        >
                            <div
                                class="p-4 flex text-sm md:text-lg justify-between items-center bg-[#7F7F7F]"
                            >
                                <span>paslon_{{ data.number }}.jpeg</span>
                                <span
                                    class="flex justify-center w-6 h-6 items-center relative"
                                >
                                    <div
                                        class="absolute w-4/5 h-0.5 origin-center rotate-45 bg-white"
                                    ></div>
                                    <div
                                        class="absolute w-4/5 h-0.5 origin-center -rotate-45 bg-white"
                                    ></div>
                                </span>
                            </div>
                            <div
                                class="w-full h-2/3 bg-[#979797] flex justify-center items-center overflow-hidden pt-2"
                            >
                                <img
                                    :src="data.image"
                                    class="object-contain h-full"
                                    :alt="'paslon_' + data.number"
                                />
                            </div>
                            <div
                                class="bg-[#979797] relative h-12 flex justify-center text-black"
                            >
                                <div
                                    class="flex mt-4 bg-[#E8E8E8] max-h-28 absolute flex-col overflow-hidden items-center w-[90%]"
                                >
                                    <div class="self-start flex text-sm">
                                        <span
                                            class="bg-[#A8C1D1] py-1 pl-3 pr-16 relative"
                                            >Nama Calon

                                            <span
                                                class="bg-[#A8C1D1] w-12 h-12 rotate-45 absolute -right-6 -top-7"
                                            ></span>
                                        </span>
                                    </div>
                                    <div
                                        class="flex flex-col text-sm h-full overflow-y-auto w-full px-6 py-2"
                                    >
                                        <span
                                            >1. {{ data.chairman.name }} ({{
                                                data.chairman.class
                                            }})</span
                                        >
                                        <span
                                            >2.
                                            {{ data.vice_chairman.name }} ({{
                                                data.vice_chairman.class
                                            }})</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex mt-24 lg:mt-0 flex-col py-4 h-full w-full overflow-auto no-scrollbar"
                    >
                        <div
                            class="lg:pl-32 px-12 text-base lg:pr-4 lg:text-lg"
                        >
                            <h5>~Visi:</h5>
                            <p v-if="data.vision">{{ data.vision }}</p>
                            <p v-else>Belum ada visi</p>
                        </div>
                        <div class="lg:pl-36 px-12 lg:pr-2 mt-8">
                            <h5 class="text-lg">~Misi:</h5>
                            <p
                                v-if="data.mission"
                                v-for="(row, index) in data.mission.split('\n')"
                            >
                                {{ row }}
                            </p>
                            <p v-else>Belum ada misi</p>
                        </div>
                    </div>
                    <div class="lg:hidden mt-8 flex justify-center">
                        <div
                            class="flex w-[90%] h-32 flex-col items-center rounded-md border-2 p-0 border-black"
                        >
                            <div
                                class="bg-[#A8C1D1] h-1/3 items-center font-medium -mt-0.5 flex justify-center border-2 w-[101%] border-black rounded z-10 relative"
                            >
                                <h4>Apakah anda yakin?</h4>
                            </div>
                            <div class="flex items-center h-2/3 gap-4">
                                <button
                                    @click="handleOpenAlertVote"
                                    class="w-24 bg-[#9CBA8F] rounded-xl py-1 border-2 border-black"
                                >
                                    Pilih
                                </button>
                                <Link :href="route('user-voter.index')">
                                    <button
                                        class="w-24 bg-[#C76E7A] rounded-xl py-1 border-2 border-black"
                                    >
                                        Kembali
                                    </button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
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
    <VAlert
        v-if="openAlertVote"
        @close="handleCloseAlertVote"
        @submit="vote"
        title="Apakah anda yakin ingin memilih pasangan ini?"
        submitButton="YA"
        cancelButton="TIDAK"
    />
</template>
