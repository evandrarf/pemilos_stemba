<script>
export default {
    layout: AppLayout,
};
</script>

<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import { array, object, string } from "vue-types";
import axios from "axios";
import { ref, onMounted } from "vue";
import { notify } from "notiwind";
import debounce from "@/composables/debounce";
import AppLayout from "@/layouts/apps.vue";
import VBreadcrumb from "@/components/VBreadCrumb/index.vue";
import VButton from "@/components/VButton/index.vue";
import VDataTable from "@/components/VDataTable/index.vue";
import VLoading from "@/components/VLoading/index.vue";
import VEmpty from "@/components/src/icons/VEmpty.vue";
import VModalForm from "./ModalForm.vue";
import VPagination from "@/components/VPagination/index.vue";

const props = defineProps({
    title: string(),
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

const getData = debounce(async (page) => {
    axios
        .get(route("candidates.getdata"), {
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
        name: "Candidates",
        active: true,
        to: route("candidates.index"),
    },
];

const heads = ["Name", "NIS", "Class", ""];

const handleAddModalForm = () => {
    openModalForm.value = true;
    updateAction.value = false;
};

const handleCloseModalForm = () => {
    openModalForm.value = false;
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

onMounted(() => {
    getData(1);
});
</script>

<template>
    <Head :title="title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Candidate</h1>
    </div>

    <div
        class="bg-white shadow-lg rounded-sm border border-slate-200"
        :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'"
    >
        <header class="block justify-between items-center sm:flex py-6 px-4">
            <h2 class="font-semibold text-slate-800">
                All Candidates
                <span class="text-slate-400 !font-medium ml">{{
                    pagination.total
                }}</span>
            </h2>
            <div
                class="mt-3 sm:mt-0 flex space-x-2 sm:justify-between justify-end"
            >
                <VButton
                    label="Add Candidate"
                    type="primary"
                    @click="handleAddModalForm"
                    class="mt-auto"
                />
            </div>
        </header>
        <VDataTable :heads="heads" :isLoading="isLoading">
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
                    {{ data.nis }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ data.class }}
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
    />
</template>
