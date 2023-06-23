<script setup>
import axios from "axios";
import debounce from "@/composables/debounce";
import { ref, watch } from "vue";
import { notify } from "notiwind";
import VDialog from "@/components/VDialog/index.vue";
import { bool, object } from "vue-types";
import VInput from "@/components/VInput/index.vue";
import VButton from "@/components/VButton/index.vue";

const props = defineProps({
    openDialog: bool().def(false),
    data: object().def({}),
    updateAction: bool().def(false),
});

const emit = defineEmits(["close", "success"]);

const form = ref({});
const isLoading = ref(false);
const formError = ref({});

const createCandidate = async () => {
    isLoading.value = true;

    axios
        .post(route("candidates.create"), form.value)
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
        :title="
            updateAction ? 'Update Candidate Data' : 'Create Candidate Data'
        "
        size="xl"
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
            <div class="py-2">
                <VInput
                    label="Candidate Name"
                    placeholder="Ex: Joshua Novalentino"
                    v-model="form.name"
                    @update:modelValue="formError.name = ''"
                    :errorMessage="formError.name"
                ></VInput>
            </div>
            <div class="py-2">
                <VInput
                    label="NIS (Nomor Induk Siswa)"
                    placeholder="Ex: 2206817984"
                    v-model="form.nis"
                    @update:modelValue="formError.nis = ''"
                    :errorMessage="formError.nis"
                ></VInput>
            </div>
            <div class="py-2">
                <VInput
                    label="Class"
                    placeholder="Ex: 11 PPLG 1"
                    v-model="form.class"
                    @update:modelValue="formError.class = ''"
                    :errorMessage="formError.class"
                ></VInput>
            </div>
        </template>
        <template v-slot:footer>
            <div class="flex flex-wrap justify-end space-x-2">
                <VButton type="default" label="Cancel" @click="emit('close')" />
                <VButton
                    :label="updateAction ? 'Update' : 'Create'"
                    :isLoading="isLoading"
                    @click="
                        updateAction ? updateCandidate() : createCandidate()
                    "
                />
            </div>
        </template>
    </VDialog>
</template>
