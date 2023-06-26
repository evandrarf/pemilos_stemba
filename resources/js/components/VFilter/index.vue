<script setup>
import { ref } from "vue";
import VButton from "@/components/VButton/index.vue";
import { string, bool } from "vue-types";

const props = defineProps({
    align: string(),
    filterExists: bool().def(false),
    minWidth: string().def("400px"),
});

const dropdownOpen = ref(false);

const emit = defineEmits(["clear", "apply"]);
</script>

<template>
    <div class="relative inline-flex">
        <button
            ref="trigger"
            class="btn bg-white border-slate-200 hover:border-slate-300 text-slate-500 hover:text-slate-600 focus:text-primary h-fit mt-auto"
            aria-haspopup="true"
            @click.prevent="dropdownOpen = !dropdownOpen"
            :aria-expanded="dropdownOpen"
        >
            <span class="sr-only">Filter</span><wbr />
            <svg
                class="w-4 h-4 fill-current"
                :class="filterExists ? 'fill-primary' : 'fill-slate-500'"
                viewBox="0 0 16 16"
            >
                <path
                    d="M9 15H7a1 1 0 010-2h2a1 1 0 010 2zM11 11H5a1 1 0 010-2h6a1 1 0 010 2zM13 7H3a1 1 0 010-2h10a1 1 0 010 2zM15 3H1a1 1 0 010-2h14a1 1 0 010 2z"
                />
            </svg>
        </button>
        <transition
            enter-active-class="transition ease-out duration-200 transform"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-out duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-show="dropdownOpen"
                class="origin-top-right z-20 absolute top-full bg-white border border-slate-200 pt-1.5 rounded shadow-lg overflow-visible mt-2 w-max"
                :class="{
                    'right-0': align === 'right',
                    'left-0': align !== 'right',
                    'min-w-[400px]': minWidth === '400px',
                    'min-w-[300px]': minWidth === '300px',
                    'min-w-[250px]': minWidth === '250px',
                }"
            >
                <div ref="dropdown">
                    <slot />

                    <div
                        class="py-2 px-3 border-t border-slate-200 bg-slate-50"
                    >
                        <ul class="flex items-center justify-end gap-x-3">
                            <VButton
                                label="Clear"
                                type="default"
                                @click="
                                    dropdownOpen = false;
                                    $emit('clear');
                                "
                                size="small"
                            />
                            <VButton
                                label="Apply"
                                type="primary"
                                @click="
                                    dropdownOpen = false;
                                    $emit('apply');
                                "
                                size="small"
                            />
                        </ul>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
