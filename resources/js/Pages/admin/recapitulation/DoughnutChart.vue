<script setup>
import { Doughnut } from "vue-chartjs";
import { ref, watch } from "vue";
import { array } from "vue-types";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    Colors,
} from "chart.js";

ChartJS.register(Title, Tooltip, Legend, ArcElement, Colors);

const props = defineProps({
    data: array(),
});

const footer = (tooltipItems) => {
    const currentData = props.data[tooltipItems[0].dataIndex];
    const count = currentData.count;
    const persentage = currentData.persentage;
    return `${count} Akun \n${persentage}%`;
};

const chartData = ref({
    labels: ["Sudah", "Belum"],
    datasets: [
        {
            backgroundColor: ["rgb(34 197 94)", "rgb(239 68 68)"],
            data: [0, 0],
        },
    ],
});

const chartOptions = ref({
    responsive: true,
    width: "50%",
    plugins: {
        legend: {
            position: "top",
        },
        title: {
            display: true,
            text: "Data Pemilih",
        },
        colors: {
            enabled: true,
        },
        interaction: {
            intersect: false,
            mode: "index",
        },
        tooltip: {
            callbacks: {
                footer: footer,
            },
        },
    },
});

watch(
    () => props.data,
    (val) => {
        chartData.value = {
            labels: ["Sudah", "Belum"],
            datasets: [
                {
                    backgroundColor: ["rgb(34 197 94)", "rgb(239 68 68)"],
                    data: val
                        .filter((item) => item.status.toLowerCase() !== "total")
                        .map((item) => item.count),
                },
            ],
        };
    }
);
</script>

<template>
    <Doughnut id="doughnut-chart" :data="chartData" :options="chartOptions" />
</template>
