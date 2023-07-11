<script>
export default {
    layout: AppLayout,
};
</script>

<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import { array, object, string } from "vue-types";
import axios, { formToJSON } from "axios";
import { ref, onMounted, reactive } from "vue";
import { notify } from "notiwind";
import debounce from "@/composables/debounce";
import AppLayout from "@/layouts/apps.vue";
import VBreadcrumb from "@/components/VBreadcrumb/index.vue";
import VButton from "@/components/VButton/index.vue";
import VDataTable from "@/components/VDataTable/index.vue";
import VLoading from "@/components/VLoading/index.vue";
import VEmpty from "@/components/src/icons/VEmpty.vue";
import VModalForm from "./ModalForm.vue";
import VPagination from "@/components/VPagination/index.vue";
import VDropdownEditMenu from "@/components/VDropdownEditMenu/index.vue";
import VEdit from "@/components/src/icons/VEdit.vue";
import VTrash from "@/components/src/icons/VTrash.vue";
import VAlert from "@/components/VAlert/index.vue";
import VDetail from "@/components/src/icons/VDetail.vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    title: string(),
    additional: object(),
});

const isLoading = ref(true);
const query = ref([]);
const pagination = ref({
    count: "",
    current_page: "",
    per_page: "",
    total: 0,
    total_pages: 1,
});
const openModalForm = ref(false);
const updateAction = ref(false);
const itemSelected = ref({});
const openAlert = ref(false);
const alertData = reactive({
    headerLabel: "",
    contentLabel: "",
    closeLabel: "",
    submitLabel: "",
});

const getData = debounce(async (page) => {
    axios
        .get(route("candidate-pairs.getdata"), {
            params: {
                page: page,
            },
        })
        .then((res) => {
            query.value = res.data.data;
            pagination.value = res.data.meta.pagination;
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
        })
        .finally(() => {
            isLoading.value = false;
        });
}, 500);

const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route("dashboard.index"),
    },
    {
        name: "Candidate Pairs",
        active: true,
        to: route("candidate-pairs.index"),
    },
];

const heads = [
    "No",
    "Image",
    "Chairman",
    "Vice Chairman",
    "Candidate Number",
    // "Vision",
    // "Mission",
    "",
];

const handleAddModalForm = () => {
    openModalForm.value = true;
    updateAction.value = false;
};

const handleCloseModalForm = () => {
    openModalForm.value = false;
    itemSelected.value = {};
    updateAction.value = false;
};

const handleSuccess = () => {
    isLoading.value = true;
    getData(pagination.value.current_page);
};

const nextPaginate = () => {
    pagination.value.current_page += 1;
    isLoading.value = true;
    getData(pagination.value.current_page);
};

const previousPaginate = () => {
    pagination.value.current_page -= 1;
    isLoading.value = true;
    getData(pagination.value.current_page);
};

const closeAlert = () => {
    openAlert.value = false;
};

const alertDelete = (data) => {
    openAlert.value = true;
    alertData.headerLabel = "Delete Candidate Pairs";
    alertData.contentLabel = `Are you sure want to delete candidate pair number ${data.number}?`;
    alertData.closeLabel = "Cancel";
    alertData.submitLabel = "Delete";
    itemSelected.value = { ...data };
};

const deleteCandidatePair = () => {
    axios
        .delete(route("candidate-pairs.delete", itemSelected.value.id))
        .then((res) => {
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.meta.message,
                },
                2500
            );
            isLoading.value = true;
            itemSelected.value = ref({});
            getData(pagination.value.current_page);
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
        })
        .finally(() => {
            openAlert.value = false;
        });
};

const handleDetailCandidatePairs = ({ id }) => {
    Inertia.visit(route("candidate-pairs.show", id));
};

onMounted(() => {
    getData(1);
});
</script>

<template>
    <Head :title="title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">
            Candidate Pairs
        </h1>
    </div>

    <div
        class="bg-white shadow-lg rounded-sm border border-slate-200"
        :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'"
    >
        <header class="block justify-between items-center sm:flex py-6 px-4">
            <h2 class="font-semibold text-slate-800">
                All Candidate Pairs
                <span class="text-slate-400 !font-medium ml">{{
                    pagination.total
                }}</span>
            </h2>
            <div
                class="mt-3 sm:mt-0 flex space-x-2 sm:justify-between justify-end"
            >
                <VButton
                    label="Add Candidate Pairs"
                    type="primary"
                    @click="handleAddModalForm"
                    class="mt-auto"
                />
            </div>
        </header>
        <VDataTable
            :heads="heads"
            :isLoading="isLoading"
            :freezeTable="false"
            head-center
        >
            <tr v-if="isLoading">
                <td
                    class="h-[100%] overflow-hidden my-2"
                    :colspan="heads.length"
                >
                    <VLoading />
                </td>
            </tr>
            <tr v-else-if="query.length === 0 && !isLoading">
                <td class="overflow-hidden my-2" :colspan="heads.length">
                    <div class="flex items-center flex-col w-full my-32">
                        <VEmpty />
                        <div
                            class="mt-9 text-slate-500 text-xl md:text-xl font-medium"
                        >
                            Result not found.
                        </div>
                    </div>
                </td>
            </tr>
            <tr v-for="(data, index) in query" :key="index" v-else>
                <td class="px-4 whitespace-nowrap fixed-left">
                    {{
                        (parseInt(pagination.current_page) - 1) * 10 +
                        (index + 1)
                    }}
                </td>
                <td class="px-4 whitespace-nowrap">
                    <div class="flex justify-start items-center h-full my-4">
                        <img
                            :src="data.image"
                            :alt="'Candidate ' + data.number"
                            class="w-40"
                        />
                    </div>
                </td>
                <td class="px-4 whitespace-nowrap">
                    {{ data.chairman.name }}
                </td>
                <td class="px-4 whitespace-nowrap">
                    {{ data.vice_chairman.name }}
                </td>
                <td class="px-4 whitespace-nowrap">
                    {{ data.number }}
                </td>
                <!-- <td class="px-4 min-w-36">
                    <p class="max-w-md w-max" v-if="data.vision">
                        {{ data.vision }}
                    </p>
                    <span v-else>Belum ada visi</span>
                </td>
                <td class="px-4 min-w-36 h-56">
                    <p class="max-w-md w-max" v-if="data.mission">
                        {{ data.mission }}
                    </p>
                    <span v-else>Belum ada misi</span>
                </td> -->
                <td class="px-4 whitespace-nowrap">
                    <VDropdownEditMenu
                        class="relative inline-flex r-0"
                        :align="'right'"
                        :last="index === query.length - 1 ? true : false"
                    >
                        <li
                            class="cursor-pointer hover:bg-slate-100"
                            @click="handleDetailCandidatePairs(data)"
                        >
                            <div class="flex items-center space-x-2 p-3">
                                <span>
                                    <VDetail color="primary" />
                                </span>
                                <span>Detail</span>
                            </div>
                        </li>
                        <li class="cursor-pointer hover:bg-slate-100">
                            <div
                                class="flex justify-between items-center space-x-2 p-3"
                                @click="alertDelete(data)"
                            >
                                <span>
                                    <VTrash color="danger" />
                                </span>
                                <span>Delete</span>
                            </div>
                        </li>
                    </VDropdownEditMenu>
                </td>
            </tr>
        </VDataTable>
        <div class="px-4 py-6">
            <VPagination
                :pagination="pagination"
                @next="nextPaginate"
                @previous="previousPaginate"
            />
        </div>
    </div>
    <VModalForm
        :update-action="updateAction"
        :data="itemSelected"
        :open-dialog="openModalForm"
        @close="handleCloseModalForm"
        @success="handleSuccess"
        :additional="additional"
    />
    <VAlert
        :open-dialog="openAlert"
        @closeAlert="closeAlert"
        @submitAlert="deleteCandidatePair"
        type="danger"
        :headerLabel="alertData.headerLabel"
        :contentLabel="alertData.contentLabel"
        :closeLabel="alertData.closeLabel"
        :submitLabel="alertData.submitLabel"
    />
</template>
