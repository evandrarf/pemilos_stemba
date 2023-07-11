<script setup>
import axios from "axios";
import debounce from "@/composables/debounce";
import { ref, watch } from "vue";
import { notify } from "notiwind";
import VDialog from "@/components/VDialog/index.vue";
import { bool, object, integer } from "vue-types";
import VInput from "@/components/VInput/index.vue";
import VButton from "@/components/VButton/index.vue";
import VKey from "@/components/src/icons/VKey.vue";
import VTooltip from "@/components/VTooltip/index.vue";
import VRadio from "@/components/VRadio/Index.vue";

const props = defineProps({
    openDialog: bool().def(false),
});

const withPasswordOptions = ref([
    {
        label: "Yes",
        value: "yes",
    },
    {
        label: "No",
        value: "no",
    },
]);

const exportStatusOptions = ref([
    {
        label: "All",
        value: "all",
    },
    {
        label: "Done",
        value: "done",
    },
    {
        label: "Not yet",
        value: "not-yet",
    },
]);

const handleExportData = () => {
    isLoading.value = true;
    axios
        .get(route("voters.students.export"), {
            responseType: "blob",
            params: form.value,
        })
        .then((res) => {
            const url = window.URL.createObjectURL(new Blob([res.data]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", "student-voter.xlsx");
            document.body.appendChild(link);
            link.click();
            link.remove();
            emit("success");
        })
        .catch((res) => {
            notify(
                {
                    type: "error",
                    group: "top",
                    text: "Export failed, please try again later",
                },
                2500
            );
        })
        .finally(() => {
            isLoading.value = false;
        });
};

const emit = defineEmits(["close", "success"]);

const form = ref({});
const isLoading = ref(false);
</script>
<template>
    <VDialog
        :showModal="props.openDialog"
        title="Export Student Voter"
        size="lg"
        wrapperClass="overflow-visible"
        @closed="closeForm"
    >
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
                    <VRadio
                        label="Export with password"
                        :required="true"
                        :options="withPasswordOptions"
                        name="with_password"
                        v-model="form.with_password"
                    />
                </div>
                <div class="py-2 col-span-2">
                    <VRadio
                        label="Export Student Status"
                        :required="true"
                        :options="exportStatusOptions"
                        name="student_status"
                        v-model="form.student_status"
                    />
                </div>
                <!-- <div class="py-2"></div>
                <div class="py-2"></div> -->
            </div>
        </template>
        <template v-slot:footer>
            <div class="flex flex-wrap justify-end space-x-2">
                <VButton type="default" label="Cancel" @click="emit('close')" />
                <VButton
                    label="Export"
                    :isLoading="isLoading"
                    @click="handleExportData()"
                />
            </div>
        </template>
    </VDialog>
</template>
