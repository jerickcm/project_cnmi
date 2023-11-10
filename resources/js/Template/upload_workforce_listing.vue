<script>
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive, watch, computed, toRefs } from "vue";
import { useToast } from "vue-toastification";
import useEmployer from "./../composables/employer";
import * as XLSX from "xlsx";

export default {
    props: [
        "preview_workforce_plan",
        "preview_workforce_listing",
        "selectedCategory",
        "detail",
        "note",
        "modal_show",
        "employer_id",
    ],
    setup(props, context) {
        var data = [];

        const input_listing = ref();

        let employer_id = computed(() => props.employer_id);

        let modal_show = computed(() => props.modal_show);

        let selectedCategory = computed(() => props.selectedCategory);

        let note = computed(() => props.note);

        let preview_workforce_plan = computed(
            () => props.preview_workforce_plan
        );

        let preview_workforce_listing = computed(
            () => props.preview_workforce_listing
        );

        let detail = computed(() => props.detail);

        let workforcelisting = () => {
            context.emit("workforcelisting", workforce__list, data_list_tally);
        };

        let clear_workforcelisting = () => {
            context.emit("clear_workforcelisting");
        };

        let call_uploadedfiles = () => {
            context.emit("call_uploadedfiles");
        };

        const submission_process = ref(false);
        const toast = useToast();
        const arrayBuffer = ref(null);
        const file_binary = ref(null);

        const filelist_doc = ref(null);
        const file_list_doc = ref(null);
        const file_listing = ref([]);
        const workforce__list = ref([]);
        const data_list_tally = ref([]);

        const XL_workforce_listing = ref([]);

        const { storeDocument, errors_details } = useEmployer();

        const add_XL_file_listing = (event) => {
            preview_workforce_listing.value = true;
            preview_workforce_plan.value = false;

            dragActive.value = true;
            droppedFile.value = event.target.files[0];

            filelist_doc.value = [...(Array.from(event.target.files) || [])];
            file_list_doc.value = event.target.files[0].name;
            computEvent(event.target.files);
        };

        const parse_listing = () => {
            XL_workforce_listing.value = Array.from(XL_workforce_listing.value);

            file_listing.value.file_id =
                XL_workforce_listing.value[0]["__EMPTY_1"];

            file_listing.value.file_id === undefined
                ? ""
                : file_listing.value.file_id;

            file_listing.value.company_name =
                XL_workforce_listing.value[0]["__EMPTY_4"];
            file_listing.value.company_name === undefined
                ? ""
                : file_listing.value.company_name;

            file_listing.value.dba = XL_workforce_listing.value[0]["__EMPTY_6"];
            file_listing.value.dba === undefined ? "" : file_listing.value.dba;

            file_listing.value.year_and_quarter =
                XL_workforce_listing.value[0]["__EMPTY_10"];
            file_listing.value.year_and_quarter === undefined
                ? ""
                : file_listing.value.year_and_quarter;

            delete XL_workforce_listing.value[0];
            delete XL_workforce_listing.value[1];
            delete XL_workforce_listing.value[2];
            delete XL_workforce_listing.value[3];
            delete XL_workforce_listing.value[4];
            delete XL_workforce_listing.value[5];

            XL_workforce_listing.value = XL_workforce_listing.value.filter(
                (val) => val
            );

            workforce__list.value = XL_workforce_listing.value;

            let workforcetally_index;

            const loopsArray = workforce__list.value.map((loopItem, index) => {
                if (loopItem["__EMPTY_1"] == "WORKFORCE TALLY") {
                    workforcetally_index = index;
                }
            });

            file_listing.value.fulltime_us_workers =
                XL_workforce_listing.value[workforcetally_index + 1][
                    "__EMPTY_6"
                ];

            file_listing.value.fulltime_non_us_workers =
                XL_workforce_listing.value[workforcetally_index + 3][
                    "__EMPTY_6"
                ];

            file_listing.value.parttime_us_workers =
                XL_workforce_listing.value[workforcetally_index + 2][
                    "__EMPTY_6"
                ];

            file_listing.value.parttime_non_us_workers =
                XL_workforce_listing.value[workforcetally_index + 4][
                    "__EMPTY_6"
                ];

            file_listing.value.name_and_position =
                XL_workforce_listing.value[workforcetally_index + 6][
                    "__EMPTY_2"
                ];

            file_listing.value.listing_day =
                XL_workforce_listing.value[workforcetally_index + 9][
                    "__EMPTY_5"
                ];

            file_listing.value.listing_month =
                XL_workforce_listing.value[workforcetally_index + 9][
                    "__EMPTY_7"
                ];

            file_listing.value.listing_year =
                XL_workforce_listing.value[workforcetally_index + 9][
                    "__EMPTY_9"
                ];

            data_list_tally.value.push({
                company_name: file_listing.value.company_name,
                dba: file_listing.value.dba,
                year_and_quarter: file_listing.value.year_and_quarter,
                name_and_position: file_listing.value.name_and_position,

                fulltime_us_workers: file_listing.value.fulltime_us_workers,
                parttime_us_workers: file_listing.value.parttime_us_workers,
                fulltime_non_us_workers:
                    file_listing.value.fulltime_non_us_workers,
                parttime_non_us_workers:
                    file_listing.value.parttime_non_us_workers,

                day: file_listing.value.listing_day,
                month: file_listing.value.listing_month,
                year: file_listing.value.listing_year,
            });

            workforce__list.value.length = workforcetally_index;

            /** Retain only workforce listing end */

            workforce__list.value.map((loopItem, index) => {
                loopItem["full_name"] = loopItem["__EMPTY_1"];
                loopItem["major_soc_code"] = loopItem["__EMPTY_2"];
                loopItem["minor_soc_code"] = loopItem["__EMPTY_3"];
                loopItem["position"] = loopItem["__EMPTY_4"];
                loopItem["employment_status"] = loopItem["__EMPTY_5"];
                loopItem["project_exemption"] =
                    loopItem["__EMPTY_6"] === undefined
                        ? ""
                        : loopItem["__EMPTY_6"];
                loopItem["wage"] = loopItem["__EMPTY_7"];
                loopItem["country_of_citizenship"] = loopItem["__EMPTY_8"];
                loopItem["visa_type_class"] = loopItem["__EMPTY_9"];
                loopItem["employment_start_date"] = loopItem["__EMPTY_10"];
                loopItem["employment_end_date"] = loopItem["__EMPTY_11"];

                delete loopItem["__EMPTY_1"];
                delete loopItem["__EMPTY_2"];
                delete loopItem["__EMPTY_3"];
                delete loopItem["__EMPTY_4"];
                delete loopItem["__EMPTY_5"];
                delete loopItem["__EMPTY_6"];
                delete loopItem["__EMPTY_7"];
                delete loopItem["__EMPTY_8"];
                delete loopItem["__EMPTY_9"];
                delete loopItem["__EMPTY_10"];
                delete loopItem["__EMPTY_11"];
            });

            workforcelisting(workforce__list, data_list_tally);
            toast.info("Load Excel File Done.");
        };

        const submit_file_listing = async () => {
            toast.info("Sending File");

            validate_onsubmit();
            submission_process.value = true;

            for (const i in filelist_doc.value) {
                data["files[" + i + "]"] = filelist_doc.value[i];
            }

            for (const i in workforce__list.value) {
                data["listing[" + i + "]"] = JSON.stringify(
                    workforce__list.value[i]
                );
            }

            file_binary.value = data;
            file_binary.value.id = employer_id;
            file_binary.value.type = "Workforce Listing";

            file_binary.value.industry = selectedCategory.value.industry;
            file_binary.value.business_types_id = selectedCategory.value.type;
            file_binary.value.company_id = detail.value.company_id;

            file_binary.value.year = detail.value.year;
            file_binary.value.quarter = detail.value.quarter;

            file_binary.value.xl_file_id = file_listing.value.file_id;
            file_binary.value.xl_company_name = file_listing.value.company_name;
            file_binary.value.xl_dba = file_listing.value.dba;
            file_binary.value.xl_year_and_quarter =
                file_listing.value.year_and_quarter;

            file_binary.value.xl_fulltime_US_workers =
                file_listing.value.fulltime_us_workers;
            file_binary.value.xl_parttime_US_worker =
                file_listing.value.parttime_us_workers;
            file_binary.value.xl_fulltime_nonUS_workers =
                file_listing.value.fulltime_non_us_workers;
            file_binary.value.xl_parttime_nonUS_workers =
                file_listing.value.parttime_non_us_workers;

            file_binary.value.xl_name_and_position =
                file_listing.value.name_and_position;
            file_binary.value.xl_listing_day = file_listing.value.listing_day;
            file_binary.value.xl_listing_month =
                file_listing.value.listing_month;
            file_binary.value.xl_listing_year = file_listing.value.listing_year;

            await storeDocument({ ...file_binary.value }).then(() => {
                if (errors_details.value) {
                    submission_process.value = false;
                    toast.error("Submit failed.");
                } else {
                    note.title = "Upload File Data";
                    note.message = "Success";
                    modal_show.value = true;
                    submission_process.value = false;
                    toast.success("Submit success.");
                }
            });

            call_uploadedfiles();
            // await server_sided();
        };

        const validate_onsubmit = () => {
            let errormessage;
            if (selectedCategory.value.industry === undefined) {
                errormessage = "Please update the Business Category";
                toast.error(errormessage);
                throw new TypeError(errormessage);
            }
            if (selectedCategory.value.type === undefined) {
                errormessage = "Please update the Business Type";
                toast.error(errormessage);
                throw new TypeError(errormessage);
            }
            if (detail.value.contact_address == null) {
                errormessage = "Contact address must be updated.";
                toast.error(errormessage);
                throw new TypeError(errormessage);
            }

            if (detail.value.contact_number == null) {
                errormessage = "Contact Number must be updated.";
                toast.error(errormessage);
                throw new TypeError(errormessage);
            }

            if (detail.value.year == null) {
                errormessage = "Year must be selected.";
                toast.error(errormessage);
                throw new TypeError(errormessage);
            }

            if (detail.value.quarter == null) {
                errormessage = "Quarter must be selected.";
                toast.error(errormessage);
                throw new TypeError(errormessage);
            }
        };

        /** dropzone  */

        const dragActive = ref(false);
        const droppedFile = ref(null);

        const toggle_active = () => {
            if (droppedFile.value == null) {
                dragActive.value = !dragActive.value;
            }
        };

        const drop = (event) => {
            droppedFile.value = event.dataTransfer.files[0];
            computEvent(event.dataTransfer.files);
        };

        const computEvent = (event_files) => {
            preview_workforce_listing.value = true;
            preview_workforce_plan.value = false;

            dragActive.value = true;
            droppedFile.value = event_files[0];

            filelist_doc.value = [...(Array.from(event_files) || [])];
            file_list_doc.value = event_files[0].name;

            fileReader(event_files);
        };

        const fileReader = (event_files) => {
            let fileReader = new FileReader();

            fileReader.readAsArrayBuffer(event_files[0]);

            fileReader.onload = (e) => {
                arrayBuffer.value = fileReader.result;

                var data = new Uint8Array(arrayBuffer.value);

                var arr = new Array();

                for (var i = 0; i != data.length; ++i) {
                    arr[i] = String.fromCharCode(data[i]);
                }

                var bstr = arr.join("");

                var workbook = XLSX.read(bstr, { type: "binary" });

                var first_sheet_name = workbook.SheetNames[0];

                var worksheet = workbook.Sheets[first_sheet_name];

                XL_workforce_listing.value = XLSX.utils.sheet_to_json(
                    worksheet,
                    {
                        raw: true,
                    }
                );

                parse_listing();
            };
        };
        const clearDropped = () => {
            droppedFile.value = null;
            dragActive.value = false;

            XL_workforce_listing.value = [];
            workforce__list.value = [];
            file_listing.value = [];
            // data_list_tally = [];
            preview_workforce_listing.value = false;

            clear_workforcelisting();
        };

        /** dropzone  */

        return {
            submit_file_listing,
            add_XL_file_listing,

            preview_workforce_listing,

            submission_process,

            toggle_active,
            drop,
            dragActive,
            droppedFile,
            clearDropped,
            input_listing,
        };
    },
};
</script>

<template>
    <div class="w-1/2 p-1">
        <form @submit.prevent="submit_file_listing">
            <label>a. Workforce Listing</label>
            <div class="max-w-xl">
                <div
                    @dragenter.prevent="toggle_active()"
                    @dragleave.prevent="toggle_active()"
                    @dragover.prevent
                    @drop.prevent="drop"
                    :class="{
                        'bg-green-100 border-green-300 ': dragActive,
                    }"
                    class="flex items-center justify-center w-full px-12 py-8 border-4 border-gray-300 border-dashed rounded"
                >
                    <div
                        class="flex flex-col items-center justify-center gap-2 text-gray-500"
                    >
                        <span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                :class="{
                                    'animate-bounce': droppedFile === null,
                                }"
                                class="w-20 h-20"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                />
                            </svg>
                        </span>
                        <p class="text-base md:text-xl font-semibold">
                            Drag a file here
                        </p>
                        <p class="text-xs md:text-sm font-semibold">
                            Or if you prefer
                        </p>

                        <label
                            for="file"
                            class="p-2 text-xs md:text-sm font-semibold leading-tight text-gray-600 bg-purple-100 border rounded cursor-pointer"
                        >
                            <span class="text-purple-500"
                                >Select a file from your device</span
                            >
                            <input
                                ref="input_listing"
                                type="file"
                                @change="add_XL_file_listing"
                                name="file"
                                id="file"
                                class="hidden"
                            />
                        </label>

                        <div
                            v-if="droppedFile !== null"
                            class="flex flex-wrap items-center justify-center gap-2 text-base font-semibold text-gray-600"
                        >
                            <span class="text-xs md:text-sm"
                                >File: {{ droppedFile.name }}</span
                            >
                            <button
                                @click="clearDropped()"
                                class="inline-flex items-center justify-center"
                            >
                                <span>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-6 h-6 text-red-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 flex flex-row flex-wrap">
                <div class="m-1">
                    <button
                        :disabled="submission_process"
                        type="submit"
                        :class="
                            submission_process
                                ? 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                : 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                        "
                    >
                        <svg
                            v-if="submission_process"
                            role="status"
                            class="inline mr-3 w-4 h-4 text-white animate-spin stroke-1"
                            viewBox="0 0 100 101"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="#E5E7EB"
                            />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentColor"
                            />
                        </svg>
                        <span>Upload</span>
                    </button>
                </div>
                <div class="m-1">
                    <button
                        type="reset"
                        @click.prevent="clearDropped"
                        class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                    >
                        Clear
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
