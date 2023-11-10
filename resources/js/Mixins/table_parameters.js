import axios from "axios";
import { ref, onMounted, reactive, watch, computed } from "vue";

export default function useTableParams() {
 
    const serverOptions = ref({
        page: 1,
        rowsPerPage: 10,
        sortBy: "id",
        sortType: "desc",
    });

    const serverOptions_listing = ref({
        page: 1,
        rowsPerPage: 10,
        sortBy: "id",
        sortType: "desc",
    });

    const serverOptions_plan = ref({
        page: 1,
        rowsPerPage: 10,
        sortBy: "id",
        sortType: "desc",
    });

    const serverOptions_workforce_listing = ref({
        page: 1,
        rowsPerPage: 10,
        sortBy: "id",
        sortType: "desc",
    });
    const serverOptions_workforce_plan = ref({
        page: 1,
        rowsPerPage: 10,
        sortBy: "id",
        sortType: "desc",
    });

    const serverOptions_workforce_listing_tally = ref({
        page: 1,
        rowsPerPage: 10,
        sortBy: "id",
        sortType: "desc",
    });
    
    const serverOptions_workforce_plan_tally = ref({
        page: 1,
        rowsPerPage: 10,
        sortBy: "id",
        sortType: "desc",
    });

    const searchParameter = reactive({
        searchField: "",
        searchValue: "",
        filterField: "",
        filterValue: "",
        select: "All",
    });

    const searchParameter_plan = reactive({
        searchField: "",
        searchValue: "",
        filterField: "",
        filterValue: "",
        select: "All",
    });
    const searchParameter_listing = reactive({
        searchField: "",
        searchValue: "",
        filterField: "",
        filterValue: "",
        select: "All",
    });
    const searchParameter_workforce_plan = reactive({
        searchField: "",
        searchValue: "",
        filterField: "",
        filterValue: "",
        select: "",
    });
    const searchParameter_workforce_listing = reactive({
        searchField: "",
        searchValue: "",
        filterField: "",
        filterValue: "",
        select: "",
    });


    const searchParameter_workforce_plan_tally = reactive({
        searchField: "",
        searchValue: "",
        filterField: "",
        filterValue: "",
        select: "",
    });
    const searchParameter_workforce_listing_tally = reactive({
        searchField: "",
        searchValue: "",
        filterField: "",
        filterValue: "",
        select: "",
    });

    return {
        serverOptions,
        serverOptions_listing,
        serverOptions_plan,
        serverOptions_workforce_plan,
        serverOptions_workforce_listing,
        serverOptions_workforce_plan_tally,
        serverOptions_workforce_listing_tally,

        searchParameter,
        searchParameter_plan,
        searchParameter_listing,
        searchParameter_workforce_plan,
        searchParameter_workforce_listing,
        searchParameter_workforce_plan_tally,
        searchParameter_workforce_listing_tally
    };
}
