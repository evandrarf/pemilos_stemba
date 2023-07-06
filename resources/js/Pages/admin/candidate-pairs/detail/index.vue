<script>
export default {
    layout: AppLayout,
};
</script>
<script setup>
import AppLayout from "@/layouts/apps.vue";
import { ref, onMounted, watch } from "vue";
import { string, object, any } from "vue-types";
import { Head, Link } from "@inertiajs/inertia-vue3";
import VBack from "@/components/src/icons/VBack.vue";
import VBreadcrumb from "@/components/VBreadcrumb/index.vue";
import VButton from "@/components/VButton/index.vue";
import VModalForm from "../ModalForm.vue";
import { notify } from "notiwind";
import { Inertia } from "@inertiajs/inertia";

const backActive = ref(false);
const openModalForm = ref(false);
const candidatePairData = ref({});
const updateAction = ref(true);
const itemSelected = ref({});

const props = defineProps({
    title: string(),
    additional: object(),
});

const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route("dashboard.index"),
    },
    {
        name: "Candidate Pairs",
        active: false,
        to: route("candidate-pairs.index"),
    },
    {
        name: "Detail",
        active: true,
        to: route("candidate-pairs.show", {
            id: props.additional.data.data.id,
        }),
    },
];

const handleFieldName = (obj, data) => {
    return Object.keys(obj)
        .find((key) => obj[key] === data)
        .toLocaleUpperCase();
};

const handleEditCandidatePairs = (data) => {
    itemSelected.value = {
        id: data.id,
        chairman_id: data.chairman_id,
        vice_chairman_id: data.vice_chairman_id,
        number: data.number,
        vision: data.vision,
        mission: data.mission,
    };
    openModalForm.value = true;
    updateAction.value = true;
};

const handleCloseModalForm = () => {
    openModalForm.value = false;
};

const handleSuccess = () => {
    openModalForm.value = false;
    notify({
        title: "Success",
        text: "Data has been updated",
        type: "success",
    });
    Inertia.reload({ preserveState: true, preserveScroll: true });
};

onMounted(() => {
    candidatePairData.value = props.additional.data.data;
    console.log(props.additional.data.data);
});

watch(
    () => props.additional.data.data,
    (value) => {
        candidatePairData.value = value;
    }
);
</script>

<template>
    <Head :title="title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-start space-x-2 items-center">
        <Link :href="route('candidate-pairs.index')" class="cursor-pointer">
            <VBack
                width="32"
                height="32"
                :is-active="backActive"
                @mouseover="backActive = true"
                @mouseout="backActive = false"
            />
        </Link>
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">
            Detail Candidate Pair
        </h1>
    </div>
    <div class="bg-white shadow-lg rounded-sm mb-8">
        <div class="flex flex-col md:flex-row md:-mr-px">
            <div class="w-1/4 p-8 border-r border-slate-200">
                <img
                    :src="candidatePairData.image"
                    alt="Foto"
                    class="w-full object-contain"
                />
            </div>
            <div class="w-4/5 flex flex-col">
                <div class="border-b p-6 flex justify-between">
                    <div class="flex">
                        <h1 class="text-2xl font-bold">
                            PASLON {{ candidatePairData.number }}
                        </h1>
                    </div>
                    <VButton
                        label="Edit"
                        type="outline-primary"
                        class="my-auto"
                        icon="VEdit"
                        @click="handleEditCandidatePairs(candidatePairData)"
                    />
                </div>
                <div class="p-6 space-y-9">
                    <!-- Chairman Information -->
                    <section>
                        <div
                            class="font-semibold text-slate-800 text-base mb-4"
                        >
                            Chairman Information
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div
                                v-for="(
                                    data, index
                                ) in candidatePairData.chairman"
                                :key="index"
                            >
                                <label
                                    class="font-medium text-sm capitalize text-slate-600 mb-1"
                                    >{{
                                        handleFieldName(
                                            candidatePairData.chairman,
                                            data
                                        )
                                    }}</label
                                >
                                <div class="font-normal text-sm text-slate-500">
                                    {{ data.toLocaleUpperCase() }}
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Vice Chairman Information -->
                    <section>
                        <div
                            class="font-semibold text-slate-800 text-base mb-4"
                        >
                            Vice Chairman Information
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div
                                v-for="(
                                    data, index
                                ) in candidatePairData.vice_chairman"
                                :key="index"
                            >
                                <label
                                    class="font-medium text-sm capitalize text-slate-600 mb-1"
                                    >{{
                                        handleFieldName(
                                            candidatePairData.vice_chairman,
                                            data
                                        )
                                    }}</label
                                >
                                <div class="font-normal text-sm text-slate-500">
                                    {{ data.toLocaleUpperCase() }}
                                </div>
                            </div>
                        </div>
                    </section>
                    <section>
                        <div
                            class="font-semibold text-slate-800 text-base mb-4"
                        >
                            Vision
                        </div>
                        <div class="w-full">
                            <div class="font-normal text-sm text-slate-500">
                                {{ candidatePairData.vision }}
                            </div>
                        </div>
                    </section>
                    <section>
                        <div
                            class="font-semibold text-slate-800 text-base mb-4"
                        >
                            Mission
                        </div>
                        <div class="w-full">
                            <div
                                class="font-normal text-sm text-slate-500 flex flex-col"
                            >
                                <span
                                    v-for="(
                                        data, index
                                    ) in candidatePairData?.mission?.split(
                                        '\n'
                                    )"
                                    >{{ data }}</span
                                >
                            </div>
                        </div>
                    </section>
                </div>
            </div>
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
</template>
