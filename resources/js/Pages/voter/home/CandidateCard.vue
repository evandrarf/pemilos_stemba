<script setup>
import { array, integer } from "vue-types";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    data: array().def({}),
    index: integer().def(0),
    length: integer().def(0),
});
</script>

<template>
    <div
        class="flex flex-col w-3/4 my-6 lg:my-4 drop-shadow-[6px_6px_0_rgba(0,0,0,0.5)] lg:drop-shadow-none lg:w-80 bg-[#E8E8E8] h-[420px] rounded-3xl border-2 overflow-hidden border-black relative"
        :class="{
            'lg:-right-9': length === index + 1,
            'lg:-left-9': index === 0 && length !== 1,
        }"
    >
        <div
            class="px-4 py-2 text-2xl border-b-2 border-black"
            :class="{
                'bg-[#A8C1D1]': index === 0,
                'bg-[#C76E7A]': index === 1,
                'bg-[#9CBA8F]': index === 2,
            }"
        >
            <span>PASLON {{ data.number }}</span>
        </div>
        <div
            class="h-[370px] w-full flex flex-col justify-between items-center"
        >
            <div
                class="h-[66%] overflow-hidden w-full flex justify-center items-center bg-[#D0D0D0]"
            >
                <img
                    :src="data.image"
                    class="h-full object-contain object-center"
                    :alt="'Foto Paslon' + data.number"
                />
            </div>
            <div class="h-[33%] mt-6">
                <Link :href="route('user-voter.detail', data.id)">
                    <div
                        class="px-6 py-1 text-2xl border-2 border-black rounded-lg"
                        :class="{
                            'bg-[#A8C1D1]': index === 0,
                            'bg-[#C76E7A]': index === 1,
                            'bg-[#9CBA8F]': index === 2,
                        }"
                    >
                        LIHAT
                    </div>
                </Link>
            </div>
        </div>
    </div>
</template>
