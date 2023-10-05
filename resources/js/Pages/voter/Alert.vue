<script setup>
import { usePage } from "@inertiajs/inertia-vue3";
import { computed } from "vue";
import { string } from "vue-types";

const user = computed(() => usePage().props.value.user);

const props = defineProps({
    title: string(),
    submitButton: string().def("Submit"),
    cancelButton: string().def("Cancel"),
});

const btn =
    "w-24 md:w-32 py-2 text-xl md:text-2xl rounded-md border-2 border-black";

const emit = defineEmits(["submit", "close"]);
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-out duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            class="fixed z-30 inset-0 bg-gray-600 opacity-80"
            @click="$emit('close')"
        ></div>
    </transition>
    <div class="fixed z-40 inset-0 flex justify-center items-center font-vcr">
        <transition
            enter-active-class="transition ease-in-out duration-200"
            enter-from-class="opacity-0 translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in-out duration-200"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-4"
        >
            <div
                class="md:w-2/5 w-[80%] h-52 flex flex-col overflow-hidden rounded-xl lg:rounded-[36px] bg-[#E8E8E8] border-2 border-black"
                @focusout="$emit('close')"
            >
                <div
                    class="bg-[#A8C1D1] px-4 lg:px-14 py-2 text-xl md:text-2xl"
                >
                    <h2 class="text-center">{{ title }}</h2>
                </div>
                <div
                    class="flex justify-center gap-4 md:gap-8 items-center h-full"
                >
                    <button
                        :class="btn"
                        class="bg-[#9CBA8F]"
                        @click="$emit('submit')"
                    >
                        {{ submitButton }}
                    </button>
                    <button
                        :class="btn"
                        class="bg-[#C76E7A]"
                        @click="$emit('close')"
                    >
                        {{ cancelButton }}
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>
