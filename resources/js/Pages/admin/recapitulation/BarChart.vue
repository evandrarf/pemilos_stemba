<script setup>
import { Bar } from "vue-chartjs";
import { ref, onMounted, reactive } from "vue";
import debounce from "@/composables/debounce";
import axios from "axios";
import { notify } from "notiwind";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    Colors,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    Colors
);

const data = ref([
    { candidate_pair: { number: 1 }, count: 0 },
    { candidate_pair: { number: 2 }, count: 0 },
    { candidate_pair: { number: 3 }, count: 0 },
]);

const getSummary = debounce(async () => {
    axios
        .get(route("recapitulation.get-summary"))
        .then((res) => {
            data.value = res.data;
            chartData.value = {
                labels: ["Perolehan suara"],
                datasets: data.value.map((item) => ({
                    label: `Paslon ${item.candidate_pair.number}`,
                    data: [item.count],
                })),
            };
            chartOptions.value = {
                ...chartOptions.value,
                scales: {
                    y: {
                        max:
                            getMax(data.value) +
                            Math.ceil((1 / 10) * getMax(data.value)),
                        ticks: {
                            stepSize: Math.ceil((1 / 10) * getMax(data.value)),
                        },
                    },
                },
            };
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
        });
});

const getMax = (data) => {
    const max = data.reduce((a, b) => (a.count > b.count ? a : b)).count;
    return max > 0 ? max : 1;
};

const chartData = ref({
    labels: ["Perolehan suara"],
    datasets: data.value.map((item) => ({
        label: `Paslon ${item.candidate_pair.number}`,
        data: [item.count],
    })),
});

const chartOptions = ref({
    responsive: true,
    width: "100%",
    plugins: {
        legend: {
            position: "top",
        },
        title: {
            display: true,
            text: "Perolehan Suara",
        },
        colors: {
            enabled: true,
            forceOverride: true,
        },
    },
    scales: {
        y: {
            min: 0,
            max: getMax(data.value) + Math.ceil((1 / 10) * getMax(data.value)),
            ticks: {
                stepSize: Math.ceil((2 / 10) * getMax(data.value)),
            },
        },
    },
});

onMounted(() => {
    getSummary();
});
</script>

<template>
    <Bar id="bar-chart" :data="chartData" :options="chartOptions" />
</template>
