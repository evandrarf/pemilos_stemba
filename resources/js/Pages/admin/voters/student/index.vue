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
import VBreadcrumb from "@/components/VBreadCrumb/index.vue";
import VButton from "@/components/VButton/index.vue";
import VDataTable from "@/components/VDataTable/index.vue";
import VLoading from "@/components/VLoading/index.vue";
import VEmpty from "@/components/src/icons/VEmpty.vue";
import VFilter from "./Filter.vue";
import VModalForm from "./ModalForm.vue";
import VPagination from "@/components/VPagination/index.vue";
import VDropdownEditMenu from "@/components/VDropdownEditMenu/index.vue";
import VEdit from "@/components/src/icons/VEdit.vue";
import VTrash from "@/components/src/icons/VTrash.vue";
import VAlert from "@/components/VAlert/index.vue";
import VBadge from "@/components/VBadge/index.vue";
import VFileExport from "@/components/src/icons/VFileExport.vue";

const props = defineProps({
    title: string(),
    additional: object(),
});

const filter = ref({});
const query = ref([]);
const isLoading = ref(true);
const openAlert = ref(false);
const searchFilter = ref("");
const itemSelected = ref({});
const openModalForm = ref(false);
const updateAction = ref(false);
const step = ref(1);
const alertData = reactive({
    headerLabel: "",
    contentLabel: "",
    closeLabel: "",
    submitLabel: "",
});
const pagination = ref({
    count: "",
    current_page: "",
    per_page: "",
    total: 0,
    total_pages: 1,
});

const heads = ["No", "Name", "Class", "Username", "Password", "Status", ""];

const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route("dashboard.index"),
    },
    {
        name: "Voters",
        active: false,
        to: route("voters.students.index"),
    },
    {
        name: "Student",
        active: true,
        to: route("voters.students.index"),
    },
];

const getData = debounce(async (page) => {
    axios
        .get(route("voters.students.getdata"), {
            params: {
                page: page,
                filter_class: filter.value.filter_class,
                filter_status: filter.value.filter_status,
                search: searchFilter.value,
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

const deleteStudentVoter = () => {
    axios
        .delete(route("voters.students.delete", itemSelected.value.id))
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
            // isLoading.value = false;
        });
};

const getStatusValue = (data) => {
    if (data === "Done") {
        return "success";
    } else {
        return "danger";
    }
};

const searchHandle = (search) => {
    searchFilter.value = search;
    isLoading.value = true;
    getData(1);
};

const applyFilter = (data) => {
    filter.value = {
        filter_status: data.filter_status,
        filter_class: data.filter_class,
    };
    isLoading.value = true;
    getData(1);
};

const handleImportModalForm = () => {
    openModalForm.value = true;
    updateAction.value = false;
    step.value = 2;
};

const handleAddModalForm = () => {
    openModalForm.value = true;
    updateAction.value = false;
    step.value = 1;
};

const handleCloseModalForm = () => {
    openModalForm.value = false;
    itemSelected.value = ref({});
    updateAction.value = false;
};

const handleSuccess = () => {
    isLoading.value = true;
    getData(pagination.value.current_page);
};

const handleEditStudentVoter = (data) => {
    itemSelected.value = { ...data };
    openModalForm.value = true;
    updateAction.value = true;
};

const alertDelete = (data) => {
    openAlert.value = true;
    alertData.headerLabel = "Delete Student";
    alertData.contentLabel = `Are you sure want to delete ${data.name}?`;
    alertData.closeLabel = "Cancel";
    alertData.submitLabel = "Delete";
    itemSelected.value = { ...data };
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

// const handleExportData = () => {
//     axios.get(route)
// }

const clearFilter = () => {
    isLoading.value = true;
    filter.value = ref({});
    getData(1);
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
            Student Voter Data
        </h1>
        <div class="flex gap-2">
            <VButton
                label="Import"
                type="success"
                icon="VFileImport"
                @click="handleImportModalForm"
                class="mt-auto"
            />
            <a
                :href="route('voters.students.export')"
                class="bg-success hover:bg-success-hover text-white disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed shadow-none btn"
                :disabled="isLoading | disabled"
            >
                <VFileExport class="mr-1" />
                Export
            </a>
            <!-- <VButton
                label="Export"
                type="success"
                icon="VFileExport"
                @click="handleExportData"
                class="mt-auto"
            /> -->
        </div>
    </div>
    <div
        class="bg-white shadow-lg rounded-sm border border-slate-200"
        :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'"
    >
        <header class="block justify-between items-center sm:flex py-6 px-4">
            <h2 class="font-semibold text-slate-800">
                All Student Voters
                <span class="text-slate-400 !font-medium ml">{{
                    pagination.total
                }}</span>
            </h2>
            <div
                class="mt-3 sm:mt-0 flex space-x-2 sm:justify-between justify-end"
            >
                <VFilter
                    @search="searchHandle"
                    @apply="applyFilter"
                    @clear="clearFilter"
                    :additional="additional"
                />
                <VButton
                    label="Add Student Voter"
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
                <td class="px-4 whitespace-nowrap h-16">
                    {{
                        (parseInt(pagination.current_page) - 1) * 10 +
                        (index + 1)
                    }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ data.name }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ data.class }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ data.username }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ data.password }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    <VBadge
                        :text="data.status"
                        :color="getStatusValue(data.status)"
                        size="sm"
                    />
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    <VDropdownEditMenu
                        class="relative inline-flex r-0"
                        :align="'right'"
                        :last="index === query.length - 1 ? true : false"
                    >
                        <li
                            class="cursor-pointer hover:bg-slate-100"
                            @click="handleEditStudentVoter(data)"
                        >
                            <div class="flex items-center space-x-2 p-3">
                                <span>
                                    <VEdit color="primary" />
                                </span>
                                <span>Edit</span>
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
        :stepForm="step"
        @close="handleCloseModalForm"
        @success="handleSuccess"
    />
    <VAlert
        :open-dialog="openAlert"
        @closeAlert="closeAlert"
        @submitAlert="deleteStudentVoter"
        type="danger"
        :headerLabel="alertData.headerLabel"
        :contentLabel="alertData.contentLabel"
        :closeLabel="alertData.closeLabel"
        :submitLabel="alertData.submitLabel"
    />
</template>
