<script setup>
import VNotification from "@/components/VNotification/Index.vue";
import VXmark from "@/components/src/icons/VXmark.vue";
</script>

<template>
    <VNotification></VNotification>
    <div
        class="w-screen h-screen flex bg-[#529B9C] items-center flex-col font-vcr relative overflow-hidden"
    >
        <div class="w-full select-none">
            <div class="w-4/5 mx-auto h-52 mt-4 bg-[#E8E8E8] flex flex-col">
                <div
                    class="bg-[#7f7f7f] text-[#E8E8E8] flex justify-between px-4 py-1.5"
                >
                    <span> Pemilos_2023/2024.jpeg </span>
                    <span><VXmark /> </span>
                </div>
                <div class="px-5 py-4 flex justify-between items-center">
                    <img
                        src="/img/logo-mpk.png"
                        class="h-[132px]"
                        alt="Logo mpk"
                    />
                    <h1 class="text-[32px] text-center">
                        FORUM PEMILIHAN KETUA DAN WAKIL OSIS TAHUN 2023/2024
                    </h1>
                    <img
                        src="/img/logo-osis.png"
                        class="h-[132px]"
                        alt="Logo osis"
                    />
                </div>
            </div>
        </div>
        <div class="w-full flex justify-center z-10">
            <slot />
        </div>
        <svg
            class="w-full absolute -bottom-24 left-0 right-0"
            viewBox="0 0 1280 412"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <circle cx="38" cy="245" r="79" fill="#E8E8E8" />
            <circle cx="156" cy="187" r="79" fill="#E8E8E8" />
            <circle cx="251" cy="238" r="79" fill="#E8E8E8" />
            <circle cx="350" cy="157" r="79" fill="#E8E8E8" />
            <circle cx="458.5" cy="128.5" r="67.5" fill="#E8E8E8" />
            <circle cx="832.5" cy="128.5" r="67.5" fill="#E8E8E8" />
            <circle cx="754.5" cy="83.5" r="67.5" fill="#E8E8E8" />
            <circle cx="1242.5" cy="238.5" r="67.5" fill="#E8E8E8" />
            <circle cx="945.5" cy="196.5" r="93.5" fill="#E8E8E8" />
            <circle cx="571.5" cy="93.5" r="93.5" fill="#E8E8E8" />
            <circle cx="1172.5" cy="157.5" r="93.5" fill="#E8E8E8" />
            <circle cx="1050.5" cy="166.5" r="63.5" fill="#E8E8E8" />
            <circle cx="665.5" cy="144.5" r="63.5" fill="#E8E8E8" />
            <path
                d="M608 126.5L-2 281.5V411.5H1281.5V250.5L784.5 126.5H608Z"
                fill="#E8E8E8"
            />
        </svg>
    </div>
</template>
