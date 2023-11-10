<script>
import { ref, onMounted, reactive, watch, computed } from "vue";
import { useToast } from "vue-toastification";
/** mixins */
import useEmployer from "./../composables/employer";

export default {
    data: () => ({
        color: "blue",
        risksHeaderClass: "bg-blue-100",
        inputClass:
            "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",
    }),
    props: ["detail", "employer"],
    setup(props, context) {
        const employer = computed(() => props.employer);
        const detail = computed(() => props.detail);
        const toast = useToast();
        const submission_process = ref(false);

        const years = computed(() => {
            let _years = [];
            for (
                let year = 2020;
                year <= new Date().getFullYear() + 10;
                year++
            ) {
                _years.push(year);
            }
            return _years;
        });

        const { errors_details, getDetail, updateYearLock, updateQuarterLock } =
            useEmployer();

        const check_year = async () => {
            // toast.info("Storing");

            await updateYearLock(employer.value.id, detail.value).then(() => {
                if (errors_details.value) {
                    submission_process.value = false;
                    // toast.error("Submit failed.");
                } else {
                    submission_process.value = false;
                    // toast.success("Submit success.");
                }
            });
        };
        const check_quarter = async () => {
            // toast.info("Storing");

            await updateQuarterLock(employer.value.id, detail.value).then(
                () => {
                    if (errors_details.value) {
                        submission_process.value = false;
                        // toast.error("Submit failed.");
                    } else {
                        submission_process.value = false;
                        // toast.success("Submit success.");
                    }
                }
            );
        };

        const select_year = () => {
            if (detail.value.checkbox_year) {
                check_year();
            }
        };

        const select_quarter = () => {
            if (detail.value.checkbox_quarter) {
                check_quarter();
            }
        };

        return {
            years,
            submission_process,
            select_year,
            check_year,
            select_quarter,
            check_quarter,
            detail,
        };
    },
};
</script>
<template>
    <div class="mt-2 w-full md:w-1/1 px-3 py-1 flex flex-wrap">
        <div class="w-1/2">
            <label>Year:</label>
            <div class="flex flex-row">
                <div class="w-3/4">
                    <select
                        @change="select_year()"
                        name="year"
                        id="year"
                        v-model="detail.year"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    >
                        <option value="0" selected="" disabled>Select</option>
                        <option
                            v-for="(value, k) in years"
                            :key="k"
                            :value="value"
                        >
                            {{ value }}
                        </option>
                    </select>
                </div>

                <div class="w-1/4 ml-2 mt-2">
                    <input
                        v-model="detail.checkbox_year"
                        :checked="detail.checkbox_year"
                        type="checkbox"
                        @change="check_year($event)"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                    />

                    <label
                        for="default-checkbox"
                        class="ml-2 text-sm font-medium text-gray-900"
                        >Lock Selection</label
                    >
                </div>
            </div>
        </div>
        <div class="w-1/2">
            <label>Quarter: (Q1 to Q4)</label>
            <div class="flex flex-wrap">
                <div class="w-2/4">
                    <select
                        @change="select_quarter()"
                        name="quarter"
                        id="quarter"
                        v-model="detail.quarter"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    >
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="w-1/4 ml-2 mt-2">
                    <input
                        id="check_quarter"
                        type="checkbox"
                        v-model="detail.checkbox_quarter"
                        :checked="detail.checkbox_quarter"
                        @change="check_quarter($event)"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                    />
                    <label
                        for="default-checkbox"
                        class="ml-2 text-sm font-medium text-gray-900"
                        >Lock Selection</label
                    >
                </div>
            </div>
        </div>
    </div>
</template>
