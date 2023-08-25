<script setup>
import { array, string, bool, integer } from "vue-types";
import { ref, watch } from "vue";
import VUser from "@/components/src/icons/VUser.vue";
import VLoading from "@/components/VLoading/index.vue";
import VSelect from "@/components/VSelect/index.vue";

const props = defineProps({
    data: array(),
    title: string(),
    dataLoading: bool(),
    span: integer().def(2),
});

const emit = defineEmits("filter");

const type_list = {
    all: "All",
    student: "Student",
    teacher: "Teacher",
};

const filter = ref({ all: "All" });

watch(
    () => props.dataLoading,
    (val) => {
        props.dataLoading = val;
    }
);
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
            <VSelect
                placeholder="Select type"
                v-model="filter"
                :options="type_list"
                :clearable="false"
                ref="filterType"
                class="w-1/3"
                @update:model-value="emit('filter', filter)"
            />
        </div>
        <div v-if="dataLoading" class="flex mt-2 items-center h-3/5">
            <VLoading class="w-8 h-8" />
        </div>
        <div
            class="flex justify-between gap-2"
            v-else-if="data.length && !dataLoading"
        >
            <div
                class="rounded border py-4 flex-grow flex"
                v-for="(item, index) in data"
                :key="index"
            >
                <div
                    class="w-1.5 rounded-r mr-4"
                    :class="{
                        'bg-blue-500': item.status === 'Total',
                        'bg-red-500': item.status === 'Not yet',
                        'bg-green-500': item.status === 'Done',
                    }"
                ></div>
                <div class="text-slate-800">
                    <div class="font-medium text-sm">
                        {{ item.status }}
                    </div>
                    <div class="mt-3">
                        <div class="font-semibold text-lg flex items-center">
                            <span class="mr-2">{{ item.count }}</span>
                            <VUser size="sm" color="text-slate-800" />
                            <span class="ml-2 text-sm" v-if="item.persentage"
                                >({{ item.persentage }}%)</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>There is no {{ title }} yet</div>
    </div>
</template>
