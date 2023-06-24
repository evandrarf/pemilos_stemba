<script setup>
import axios from "axios";
import debounce from "@/composables/debounce";
import { ref, watch } from "vue";
import { notify } from "notiwind";
import VDialog from "@/components/VDialog/index.vue";
import { bool, object } from "vue-types";
import VInput from "@/components/VInput/index.vue";
import VButton from "@/components/VButton/index.vue";
import VKey from "@/components/src/icons/VKey.vue";

const props = defineProps({
    openDialog: bool().def(false),
    data: object().def({}),
    updateAction: bool().def(false),
});

const emit = defineEmits(["close", "success"]);

const form = ref({});
const isLoading = ref(false);
const formError = ref({});

const createStudentVoter = async () => {
    isLoading.value = true;

    axios
        .post(route("voters.students.create"), form.value)
        .then((res) => {
            emit("success");
            emit("close");
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.meta.message,
                },
                2500
            );
        })
        .catch((res) => {
            const result = res.response.data;
            const metaError = res.response.data.meta?.error;
            if (result.hasOwnProperty("errors")) {
                formError.value = ref({});
                Object.keys(result.errors).map((key) => {
                    formError.value[key] = result.errors[key].toString();
                });
            }
            if (metaError) {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: metaError,
                    },
                    2500
                );
            } else {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: result.message,
                    },
                    2500
                );
            }
        })
        .finally(() => {
            isLoading.value = false;
        });
};

const updateCandidate = async () => {
    isLoading.value = true;

    axios
        .put(route("candidates.update", form.value.id), { ...form.value })
        .then((res) => {
            emit("success");
            emit("close");
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.meta.message,
                },
                2500
            );
        })
        .catch((res) => {
            const result = res.response.data;
            const metaError = res.response.data.meta?.error;
            if (result.hasOwnProperty("errors")) {
                formError.value = ref({});
                Object.keys(result.errors).map((key) => {
                    formError.value[key] = result.errors[key].toString();
                });
            }
            if (metaError) {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: metaError,
                    },
                    2500
                );
            } else {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: result.message,
                    },
                    2500
                );
            }
        })
        .finally(() => {
            isLoading.value = false;
        });
};

const generatePassword = () => {
    const length = 10;
    const char =
        "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!_&=+";
    let password = "";
    for (let i = 0; i < length; i++) {
        password += char.charAt(Math.floor(Math.random() * char.length));
    }
    form.value.password = password;
};

watch(
    () => props.data,
    (newVal, oldVal) => {
        if (newVal) {
            form.value = newVal;
        }
    }
);
</script>
<template>
    <VDialog
        :showModal="props.openDialog"
        :title="updateAction ? 'Update Student Voter' : 'Create Student Voter'"
        size="2xl"
        wrapperClass="overflow-visible"
        @closed="closeForm"
    >
        <template v-slot:close>
            <button
                class="text-slate-400 hover:text-slate-500"
                @click="$emit('close')"
            >
                <div class="sr-only">Close</div>
                <svg class="w-4 h-4 fill-current">
                    <path
                        d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"
                    />
                </svg>
            </button>
        </template>
        <template v-slot:content>
            <div class="grid grid-cols-2 gap-3">
                <div class="py-2 col-span-2">
                    <VInput
                        label="Name"
                        :required="true"
                        placeholder="Ex: Muhammad Salimul Qolbi"
                        v-model="form.name"
                        @update:modelValue="formError.name = ''"
                        :errorMessage="formError.name"
                    />
                </div>
                <div class="py-2">
                    <VInput
                        label="Class"
                        placeholder="Ex: 11 PPLG 1"
                        v-model="form.class"
                        :required="true"
                        @update:modelValue="formError.class = ''"
                        :errorMessage="formError.class"
                    />
                </div>
                <div class="py-2">
                    <VInput
                        label="Username"
                        placeholder="Ex: 2206817984"
                        v-model="form.username"
                        :tooltip="true"
                        tooltipBg="white"
                        position="top"
                        :required="true"
                        @update:modelValue="formError.username = ''"
                        :errorMessage="formError.username"
                    >
                        <template v-slot:tooltip>
                            <div class="text-xs">
                                <div class="font-semibold text-slate-800 mb-1">
                                    Student Voter Username
                                </div>
                                <div class="mb-0.5">
                                    Menggunakan NIS (Nomor Induk Siswa) dari
                                    siswa yang bersangkutan
                                </div>
                            </div>
                        </template>
                    </VInput>
                </div>
                <div class="py-2 col-span-2">
                    <VInput
                        label="Password"
                        placeholder="Min: 8 characters"
                        v-model="form.password"
                        :tooltip="true"
                        tooltipBg="white"
                        position="right"
                        :prefix="true"
                        @update:modelValue="formError.password = ''"
                        :errorMessage="formError.password"
                    >
                        <template v-slot:tooltip>
                            <div class="text-xs">
                                <div class="font-semibold text-slate-800 mb-1">
                                    Student Voter Password
                                </div>
                                <div class="mb-0.5">
                                    Akan digenerate otomatis jika kosong atau
                                    tekan tombol generate untuk generate
                                    password
                                </div>
                            </div>
                        </template>
                        <template v-slot:prefix>
                            <button
                                @click="generatePassword"
                                class="ml-2 bg-blue-500 py-2 px-3 rounded-md flex text-sm items-center text-white"
                            >
                                <VKey color="text-white" />
                                <span class="ml-2">Generate</span>
                            </button>
                        </template>
                    </VInput>
                </div>
            </div>
        </template>
        <template v-slot:footer>
            <div class="flex flex-wrap justify-end space-x-2">
                <VButton type="default" label="Cancel" @click="emit('close')" />
                <VButton
                    :label="updateAction ? 'Update' : 'Create'"
                    :isLoading="isLoading"
                    @click="
                        updateAction ? updateCandidate() : createStudentVoter()
                    "
                />
            </div>
        </template>
    </VDialog>
</template>
