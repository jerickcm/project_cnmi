<script>
import { Link } from "@inertiajs/inertia-vue3";
import { ref, defineEmits } from "vue";

export default {
    data: () => ({
        color: "blue",
        risksHeaderClass: "bg-blue-100",
        inputClass:
            "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",
    }),
    props: ["detail"],
    setup(props, context) {},
};
</script>
<template>
    <div class="py-1 font-semibold">II. Contact Information:</div>
    <div class="flex flex-wrap -mx-3">
        <div class="w-full md:w-1/2 px-3 py-1 relative">
            <input
                v-model="detail.contact_address"
                type="text"
                id="floating_filled"
                class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50  border-0 border-b-2 border-gray-300 appearance-none    focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" "
            />
            <label
                for="floating_filled"
                class="absolute text-md text-gray-500  duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-2.5 peer-focus:text-blue-600  peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4"
                >Contact Address:</label
            >
        </div>
        <div class="w-full md:w-1/2 px-3 py-1 relative">
            <input
                v-model="detail.contact_number"
                type="text"
                id="floating_filled"
                class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50border-0 border-b-2 border-gray-300 appearance-none    focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" "
            />
            <label
                for="floating_filled"
                class="absolute text-md text-gray-500  duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-2.5 peer-focus:text-blue-600  peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4"
                >Contact Number:</label
            >
        </div>
    </div>
</template>
