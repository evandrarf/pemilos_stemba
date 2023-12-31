<script setup>
import { ref, computed, watch } from "vue";
import { bool } from "vue-types";
import axios from "axios";
import VXmark from "@/components/src/icons/VXmark.vue";
import { notify } from "notiwind";
import { usePage } from "@inertiajs/inertia-vue3";
import VEyeClosed from "@/components/src/icons/VEyeClosed.vue";
import VEyeOpen from "@/components/src/icons/VEyeOpen.vue";

const user = computed(() => usePage().props.value.user);

const form = ref({
    username: "",
    password: "",
});

const isLoading = ref(false);
const props = defineProps({
    isStudent: bool().def(true),
});
const showPassword = ref(false);

const emit = defineEmits(["changeIsStudent"]);

const handleLogin = () => {
    isLoading.value = true;
    axios
        .post(route("user-voter.login"), {
            type: props.isStudent ? "student" : "teacher",
            ...form.value,
        })
        .then((res) => {
            localStorage.setItem("showPopup", "true");
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
            console.log(res.response.data.message);
        })
        .finally(() => (isLoading.value = false));
};

const changeIsStudent = (value) => {
    emit("changeIsStudent", value);
};

watch(
    () => props.isStudent,
    (value) => {
        form.value.username = "";
        form.value.password = "";
    }
);
</script>

<template>
    <div
        class="w-2/3 h-max mt-3 lg:mt-0 md:w-[340px] relative flex justify-center items-start"
    >
        <div
            class="w-full h-full border-2 border-black relative -top-8 rounded-[30px] bg-[#E8E8E8] flex flex-col overflow-hidden"
        >
            <div
                class="bg-[#B08BBB] w-full h-12 flex justify-between items-center px-5 text-xl border-b-2 border-black"
            >
                <span>PEMILOS</span>
                <span>
                    <span class="py-1 px-2 border border-black">
                        <VXmark class="text-xl" />
                    </span>
                </span>
            </div>
            <div class="flex flex-col items-center py-4">
                <h2 class="text-2xl lg:text-3xl">
                    LOG-IN {{ isStudent ? "SISWA" : "GURU" }}
                </h2>
                <img
                    src="img/logo-smkn-7-smg-putih.png"
                    class="w-1/2 mt-2"
                    alt="logo smk"
                />
                <div class="flex md:hidden flex-col">
                    <button
                        class="mt-6 bg-[#B08BBB] text-lg rounded py-1 disabled:bg-slate-200 disabled:cursor-not-allowed disabled:text-gray-500 disabled:border-gray-500 border-2 border-black px-8"
                        type="submit"
                        @click="changeIsStudent(false)"
                        :disabled="isLoading"
                        :class="{ 'text-white': !isStudent }"
                    >
                        Guru
                    </button>
                    <button
                        class="mt-6 bg-[#B08BBB] text-lg rounded py-1 disabled:bg-slate-200 disabled:cursor-not-allowed disabled:text-gray-500 disabled:border-gray-500 border-2 border-black px-8"
                        type="submit"
                        @click="changeIsStudent(true)"
                        :disabled="isLoading"
                        :class="{ 'text-white': isStudent }"
                    >
                        Siswa
                    </button>
                </div>
                <input
                    type="text"
                    :placeholder="isStudent ? 'Username (NIS)' : 'Username'"
                    class="bg-transparent outline-none border border-black rounded-3xl text-[16px] active:border-black px-4 py-2 mt-4 w-4/5 lg:w-2/3"
                    v-model="form.username"
                />
                <div
                    class="flex items-center mt-4 border outline-none border-black rounded-3xl active:border-black pl-2 pr-3 w-4/5 lg:w-2/3"
                >
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Password"
                        class="bg-transparent border-none w-full outline-none text-[16px] py-2"
                        v-model="form.password"
                    />
                    <VEyeOpen
                        @click="(e) => (showPassword = false)"
                        class="cursor-pointer"
                        v-if="showPassword"
                    />
                    <VEyeClosed
                        class="cursor-pointer"
                        @click="(e) => (showPassword = true)"
                        v-else
                    />
                </div>
                <button
                    class="mt-6 bg-[#B08BBB] text-xl rounded py-1 disabled:bg-slate-200 disabled:cursor-not-allowed disabled:text-gray-500 disabled:border-gray-500 border-2 border-black px-8 md:text-2xl"
                    type="submit"
                    @click="handleLogin"
                    :disabled="isLoading"
                >
                    LOG-IN
                </button>
            </div>
        </div>
        <div
            class="absolute hidden md:flex flex-col gap-8 justify-start text-2xl -left-24 top-12"
        >
            <div
                class="w-24 py-2 flex cursor-pointer justify-center bg-[#B08BBB] items-center"
                @click="changeIsStudent(false)"
                :class="{
                    'bg-[#816589]': !isStudent,
                    'text-white': !isStudent,
                }"
            >
                Guru
            </div>
            <div
                class="w-24 py-2 flex cursor-pointer bg-[#B08BBB] justify-center items-center"
                :class="{ 'bg-[#816589]': isStudent, 'text-white': isStudent }"
                @click="changeIsStudent(true)"
            >
                Siswa
            </div>
        </div>
    </div>
</template>
