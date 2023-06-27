<script setup>
import axios from "axios";
import debounce from "@/composables/debounce";
import { ref, watch } from "vue";
import { notify } from "notiwind";
import VDialog from "@/components/VDialog/index.vue";
import { any, bool, object } from "vue-types";
import VInput from "@/components/VInput/index.vue";
import VButton from "@/components/VButton/index.vue";
import VSelect from "@/components/VSelect/index.vue";
import VTextArea from "@/components/VTextArea/index.vue";

const props = defineProps({
    openDialog: bool().def(false),
    data: object().def({}),
    updateAction: bool().def(false),
    additional: object().def({}),
});

const emit = defineEmits(["close", "success"]);

const form = ref({});
const isLoading = ref(false);
const formError = ref({});

const createCandidatePair = async () => {
    isLoading.value = true;
    const fd = new FormData();

    if (form.value.file != null) {
        fd.append("file", form.value.file, form.value.file.name);
    }
    Object.keys(form.value).forEach((key) => {
        fd.append(key, form.value[key]);
    });

    axios
        .post(route("candidate-pairs.create"), fd)
        .then((res) => {
            emit("success");
            emit("close");
            form.value = ref({});

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

const updateCandidatePair = async () => {
    isLoading.value = true;
    const fd = new FormData();

    if (form.value.file != null) {
        fd.append("file", form.value.file, form.value.file.name);
    }

    Object.keys(form.value).forEach((key) => {
        fd.append(key, form.value[key]);
    });

    axios
        .post(route("candidate-pairs.update", form.value.id), fd)
        .then((res) => {
            emit("success");
            emit("close");
            form.value = ref({});

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

const fileSelected = (evt) => {
    formError.value.file = "";
    form.value.file = evt.target.files[0];
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
            updateAction ? 'Update Candidate Pair' : 'Create Candidate Pair'
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
                <VSelect
                    placeholder="Choose Chairman"
                    label="Chairman"
                    :required="true"
                    v-model="form.chairman_id"
                    :options="additional.candidate_list"
                    :errorMessage="formError.chairman_id"
                    @update:modelValue="formError.chairman_id = ''"
                    s
                />
            </div>
            <div class="py-2">
                <VSelect
                    placeholder="Choose Vice Chairman"
                    label="Vice Chairman"
                    :required="true"
                    v-model="form.vice_chairman_id"
                    :options="additional.candidate_list"
                    :errorMessage="formError.vice_chairman_id"
                    @update:modelValue="formError.vice_chairman_id = ''"
                    s
                />
            </div>
            <div class="col-span-2">
                <div>
                    <label
                        class="block text-sm font-medium text-slate-600 mb-1"
                        for="leaveFile"
                        >Image
                        <!-- <span class="text-rose-500" v-if="!updateAction"
                            >*</span
                        > -->
                    </label>
                    <input
                        class="block w-full cursor-pointer bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md"
                        type="file"
                        id="leaveFile"
                        @change="fileSelected"
                        accept="image/png, image/jpeg, image/jpg"
                    />
                    <div
                        class="text-xs mt-1"
                        :class="[
                            {
                                'text-rose-500': formError.file,
                            },
                        ]"
                        v-if="formError.file"
                    >
                        {{ formError.file }}
                    </div>
                </div>
            </div>
            <div class="py-2">
                <VInput
                    label="Candidate Number"
                    placeholder="Ex: 1"
                    :required="true"
                    v-model="form.number"
                    @update:modelValue="formError.number = ''"
                    :errorMessage="formError.number"
                ></VInput>
            </div>
            <div class="py-2">
                <VTextArea
                    label="Vision"
                    placeholder="Ex: Menjadikan OSIS SMKN 7 SEMARANG adalah organisasi yang progresif, dinamis, produktif, bertanggung jawab dan mampu menjadi wadah untuk menampung serta menyampaikan inspirasi dan aspirasi siswa"
                    v-model="form.vision"
                    @update:modelValue="formError.vision = ''"
                    :errorMessage="formError.vision"
                    rows="4"
                />
            </div>
            <div class="py-2">
                <VTextArea
                    label="Mission"
                    placeholder="Ex:
1.menjalin hubungan yang baik antar organisasi dan ekstrakulikuler
2.mengembangkan dan melaksanakan program kerja sebelumnya
3.mendukung potensi bakat dan minat siswa melalui kegiatan akademik maupun non akademik di berbagai bidang
4.Meningkatan etos kerja sehingga terwujudnya kepengurusan yang solid dan profesional"
                    v-model="form.mission"
                    @update:modelValue="formError.mission = ''"
                    :errorMessage="formError.mission"
                    rows="8"
                />
            </div>
        </template>
        <template v-slot:footer>
            <div class="flex flex-wrap justify-end space-x-2">
                <VButton type="default" label="Cancel" @click="emit('close')" />
                <VButton
                    :label="updateAction ? 'Update' : 'Create'"
                    :isLoading="isLoading"
                    @click="
                        updateAction
                            ? updateCandidatePair()
                            : createCandidatePair()
                    "
                />
            </div>
        </template>
    </VDialog>
</template>
