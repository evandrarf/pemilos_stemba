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
import VFilter from "./Filter.vue";
import VModalForm from "./ModalForm.vue";
import VPagination from "@/components/VPagination/index.vue";
import VDropdownEditMenu from "@/components/VDropdownEditMenu/index.vue";
import VEdit from "@/components/src/icons/VEdit.vue";
import VTrash from "@/components/src/icons/VTrash.vue";
import VAlert from "@/components/VAlert/index.vue";
import VBadge from "@/components/VBadge/index.vue";
import VEyeOpen from "@/components/src/icons/VEyeOpen.vue";
import VEyeClosed from "@/components/src/icons/VEyeClosed.vue";
import VExportModalForm from "./ExportModalForm.vue";

const props = defineProps({
    title: string(),
    additional: object(),
});

const filter = ref({});
const query = ref([]);
const isLoading = ref(true);
const openAlertDeleteTeacher = ref(false);
const openAlertDeleteAllTeacher = ref(false);
const searchFilter = ref("");
const itemSelected = ref({});
const openModalForm = ref(false);
const updateAction = ref(false);
const classList = ref([]);
const step = ref(1);
const show = ref({});
const disabled = ref(true);
const openExportModalForm = ref(false);
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

const heads = ["No", "Name", "Username", "Password", "Status", ""];

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
        name: "Teacher",
        active: true,
        to: route("voters.teachers.index"),
    },
];

const getData = debounce(async (page) => {
    axios
        .get(route("voters.teachers.getdata"), {
            params: {
                page: page,
                filter_status: filter.value.filter_status,
                search: searchFilter.value,
            },
        })
        .then((res) => {
            query.value = res.data.data;
            pagination.value = res.data.meta.pagination;
            disabled.value = query.value.length === 0;

            if (query.value.length > 0) {
                show.value = query.value.map((item) => {
                    show.value[item.id] = false;
                });
            }
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

const deleteTeacherVoter = () => {
    axios
        .delete(route("voters.teachers.delete", itemSelected.value.id))
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
            openAlertDeleteTeacher.value = false;
            // isLoading.value = false;
        });
};

const deleteAllTeacherVoter = () => {
    axios
        .delete(route("voters.teachers.delete-all"))
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
            getData(1);
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
            openAlertDeleteAllTeacher.value = false;
        });
};

const getStatusValue = (data) => {
    if (data === "Done") {
        return "success";
    } else {
        return "danger";
    }
};

const handleShowPassword = (id) => {
    show.value[id] = !show.value[id];
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
    openAlertDeleteTeacher.value = true;
    alertData.headerLabel = "Delete Teacher";
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
    openAlertDeleteAllTeacher.value = false;
    openAlertDeleteTeacher.value = false;
};

const handleExportData = () => {
    axios
        .get(route("voters.teachers.export"), { responseType: "blob" })
        .then((res) => {
            const url = window.URL.createObjectURL(new Blob([res.data]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", "teacher-voter.xlsx");
            document.body.appendChild(link);
            link.click();
            link.remove();
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
        });
};

const handleOpenExportModalForm = () => {
    openExportModalForm.value = true;
};

const handleCloseExportModalForm = () => {
    openExportModalForm.value = false;
};

const handleSuccessExport = () => {
    openExportModalForm.value = false;
};

const handleDeleteAllTeacherVoter = () => {
    openAlertDeleteAllTeacher.value = true;
    alertData.headerLabel = "Delete All Teacher";
    alertData.contentLabel =
        "Are you sure want to delete all teacher? (This action cannot be undone)";
    alertData.closeLabel = "Cancel";
    alertData.submitLabel = "Delete";
    itemSelected.value = { ...itemSelected };
};

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
            Teacher Voter Data
        </h1>
        <div class="flex gap-2">
            <VButton
                label="Import"
                type="success"
                icon="VFileImport"
                @click="handleImportModalForm"
                class="mt-auto"
            />
            <VButton
                label="Export"
                type="success"
                icon="VFileExport"
                @click="handleOpenExportModalForm"
                class="mt-auto"
                :disabled="isLoading | disabled"
            />
            <VButton
                label="Delete All"
                type="danger"
                @click="handleDeleteAllTeacherVoter"
                icon="VTriangleExclamation"
                class="mt-auto"
                :disabled="isLoading | disabled"
            />
        </div>
    </div>
    <div
        class="bg-white shadow-lg rounded-sm border border-slate-200"
        :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'"
    >
        <header class="block justify-between items-center sm:flex py-6 px-4">
            <h2 class="font-semibold text-slate-800">
                All Teacher Voters
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
                    :classList="classList"
                />
                <VButton
                    label="Add Teacher Voter"
                    type="primary"
                    @click="handleAddModalForm"
                    class="mt-auto"
                />
            </div>
        </header>
        <VDataTable
            :heads="heads"
            :isLoading="isLoading"
            :freezeTable="true"
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
                        (parseInt(pagination.current_page) - 1) * 25 +
                        (index + 1)
                    }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ data.name }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ data.username }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    <div class="flex items-center w-20 justify-between">
                        <span v-if="!show[data.id]"
                            >&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</span
                        >
                        <span v-else>
                            {{ data.password }}
                        </span>
                        <span
                            @click="handleShowPassword(data.id)"
                            class="cursor-pointer"
                        >
                            <VEyeClosed v-if="!show[data.id]" />
                            <VEyeOpen v-else />
                        </span>
                    </div>
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
    <VExportModalForm
        :open-dialog="openExportModalForm"
        @success="handleSuccessExport"
        @close="handleCloseExportModalForm"
    />
    <VModalForm
        :update-action="updateAction"
        :data="itemSelected"
        :open-dialog="openModalForm"
        :stepForm="step"
        @close="handleCloseModalForm"
        @success="handleSuccess"
    />
    <VAlert
        :open-dialog="openAlertDeleteAllTeacher"
        @closeAlert="closeAlert"
        @submitAlert="deleteAllTeacherVoter"
        type="danger"
        :headerLabel="alertData.headerLabel"
        :contentLabel="alertData.contentLabel"
        :closeLabel="alertData.closeLabel"
        :submitLabel="alertData.submitLabel"
    />
    <VAlert
        :open-dialog="openAlertDeleteTeacher"
        @closeAlert="closeAlert"
        @submitAlert="deleteTeacherVoter"
        type="danger"
        :headerLabel="alertData.headerLabel"
        :contentLabel="alertData.contentLabel"
        :closeLabel="alertData.closeLabel"
        :submitLabel="alertData.submitLabel"
    />
</template>
