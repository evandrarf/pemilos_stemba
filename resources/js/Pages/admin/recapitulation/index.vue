<script>
export default {
    layout: AppLayout,
};
</script>

<script setup>
import AppLayout from "@/layouts/apps.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ref, onMounted } from "vue";
import { string } from "vue-types";
import VBreadcrumb from "@/components/VBreadcrumb/index.vue";
import debounce from "@/composables/debounce";
import axios from "axios";
import VSummary from "./Summary.vue";
import VVoterSummary from "./VoterSummary.vue";
import BarChart from "./BarChart.vue";
import DoughnutChart from "./DoughnutChart.vue";

const summary = ref([]);
const summaryLoading = ref(true);
const voterSummary = ref([]);
const voterSummaryLoading = ref(true);
const filterTypeValue = ref("all");

const getSummary = debounce(async () => {
    axios
        .get(route("recapitulation.get-summary"))
        .then((res) => {
            summary.value = res.data;
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
        .finally(() => (summaryLoading.value = false));
});

const getVoterSummary = debounce(async () => {
    axios
        .get(route("recapitulation.get-voter-summary"), {
            params: {
                type: filterTypeValue.value,
            },
        })
        .then((res) => {
            voterSummary.value = res.data;
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
        .finally(() => (voterSummaryLoading.value = false));
});

const props = defineProps({
    title: string(),
});

const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route("dashboard.index"),
    },
    {
        name: "Recapitulation",
        active: true,
        to: route("recapitulation.index"),
    },
];

const initData = () => {
    summaryLoading.value = true;
    voterSummaryLoading.value = true;
    getVoterSummary();
    getSummary();
};

const handleFilter = (data) => {
    filterTypeValue.value = data;
    voterSummaryLoading.value = true;
    getVoterSummary();
};

onMounted(() => {
    initData();
});
</script>

<template>
    <Head :title="title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">
            Recapitulation
        </h1>
    </div>

    <div class="grid grid-cols-4 gap-4">
        <VSummary
            :data="summary"
            :data-loading="summaryLoading"
            title="summary"
            :span="2"
        />
        <VVoterSummary
            :data="voterSummary"
            :data-loading="voterSummaryLoading"
            title="User summary"
            :span="2"
            @filter="handleFilter"
        />
        <div class="col-span-2 mb-8">
            <BarChart />
        </div>
        <div class="col-span-2 flex justify-center mb-8">
            <div class="w-1/2">
                <DoughnutChart :data="voterSummary" />
            </div>
        </div>
    </div>
</template>
