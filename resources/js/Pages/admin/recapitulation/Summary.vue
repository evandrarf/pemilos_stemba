<script setup>
import { array, string, bool, integer } from "vue-types";
import VUser from "@/components/src/icons/VUser.vue";
import VLoading from "@/components/VLoading/index.vue";

const props = defineProps({
    data: array(),
    title: string(),
    dataLoading: bool(),
    span: integer().def(2),
});
</script>

<template>
    <div
        class="bg-white shadow-lg rounded-md mb-8 p-4"
        :class="{
            'col-span-2': span === 2,
            'col-span-1': span === 1,
            'col-span-3': span === 3,
        }"
    >
        <div class="mb-5 flex justify-between items-center">
            <h2 class="text-xl text-slate-800 font-semibold capitalize">
                {{ title }}
            </h2>
        </div>
        <div class="flex justify-between gap-2" v-if="data.length">
            <div
                class="rounded border py-4 flex-grow flex"
                v-for="(item, index) in data"
                :key="index"
                v-if="!dataLoading"
            >
                <div class="w-1.5 rounded-r mr-4 bg-blue-500"></div>
                <div class="text-slate-800">
                    <div class="font-medium text-sm">
                        PASLON {{ item.candidate_pair.number }}
                    </div>
                    <div class="mt-3">
                        <div class="font-semibold text-lg flex items-center">
                            <span class="mr-2">{{ item.count }}</span>
                            <VUser size="sm" color="text-slate-800" />
                            <span class="ml-2 text-sm"
                                >({{ item.persentage }}%)</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="dataLoading" class="flex mt-2">
            <VLoading class="w-8 h-8" />
        </div>
        <div v-else>There is no {{ title }} yet</div>
    </div>
</template>
