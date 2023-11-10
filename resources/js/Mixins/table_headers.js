import { ref } from "vue";

export default function useHeaders() {

    /** dol */

    const headers_by_company = ref([
        { text: "Category", value: "industry", sortable: true },
        { text: "Business Type", value: "type", sortable: true },
        { text: "Company", value: "view", sortable: true },
        { text: "Email", value: "email", sortable: true },
        { text: "Company Personel", value: "full_name", sortable: true },
        { text: "Clearance", value: "clearance", sortable: true },
        { text: "Note", value: "note", sortable: true },
        { text: "Action", value: "action", sortable: false },
    ]);

    const headers_uploaded_listing = ref([
        { text: "Category", value: "industry", sortable: true },
        { text: "Business Type", value: "businesstype", sortable: true },
        { text: "Report", value: "type", sortable: true },
        { text: "Year", value: "year", sortable: true },
        { text: "Quarter", value: "quarter", sortable: true },
        { text: "Document", value: "link", sortable: true },
        { text: "Action", value: "action", sortable: false },
    ]);

    const headers_uploaded_plan = ref([
        { text: "Category", value: "industry", sortable: true },
        { text: "Business Type", value: "businesstype", sortable: true },
        { text: "Report", value: "type", sortable: true },
        { text: "Year", value: "year", sortable: true },
        { text: "Quarter", value: "quarter", sortable: true },
        { text: "Document", value: "link", sortable: true },
        { text: "Action", value: "action", sortable: false },
    ]);

    const headers_workforce_plan = ref([
        { text: "Employee Name", value: "full_name", sortable: true },
        { text: "Employment Status", value: "employment", sortable: true },
        {
            text: "VISA Expiration",
            value: "visa_expiration_date",
            sortable: true,
        },
        {
            text: "O*NET Occupational Classification Code",
            value: "occupational_classification_code",
            sortable: true,
        },
        {
            text: "Timetable for replacement of foreign workers",
            value: "timetable_replacement_foreignworkers",
            sortable: true,
        },
        {
            text: "Specific Replacement Plan",
            value: "specific_replacement_plan",
            sortable: true,
        },
    ]);

    const headers_workforce_plan_edit = ref([
        { text: "Employee Name", value: "full_name", sortable: true },
        { text: "Employment Status", value: "employment", sortable: true },
        {
            text: "VISA Expiration",
            value: "visa_expiration_date",
            sortable: true,
        },
        {
            text: "O*NET Occupational Classification Code",
            value: "occupational_classification_code",
            sortable: true,
        },
        {
            text: "Timetable for replacement of foreign workers",
            value: "timetable_replacement_foreignworkers",
            sortable: true,
        },
        {
            text: "Specific Replacement Plan",
            value: "specific_replacement_plan",
            sortable: true,
        },
        {
            text: "Action",
            value: "action",
            sortable: true,
        },
    ]);

    const headers_workforce_listing = ref([
        { text: "Employee Name", value: "full_name", sortable: true },
        { text: "Major Soc Code", value: "major_soc_code", sortable: true },
        { text: "Minor Soc Code", value: "minor_soc_code", sortable: true },
        { text: "Position", value: "position", sortable: true },
        {
            text: "Employment Status",
            value: "employment_status",
            sortable: true,
        },
        { text: "Wage", value: "wage", sortable: true },
        {
            text: "Country of Citizenship",
            value: "country_of_citizenship",
            sortable: true,
        },
        {
            text: "VISA TYPE / CLASS",
            value: "visa_type_class",
            sortable: true,
        },
        {
            text: "Start Date of Employment",
            value: "employment_start_date",
            sortable: true,
        },
        {
            text: "End Date of Employment",
            value: "employment_end_date",
            sortable: true,
        },
    ]);

    /** employer */

    const headers_uploaded_files = ref([
        { text: "Category", value: "industry", sortable: true },
        { text: "Business Type", value: "businesstype", sortable: true },
        { text: "Report", value: "type", sortable: true },
        { text: "Year", value: "year", sortable: true },
        { text: "Quarter", value: "quarter", sortable: true },
        { text: "Document", value: "link", sortable: true },
        { text: "Action", value: "action", sortable: false },
    ]);

    /** Tally plan */

    const headers_plan_tally = ref([
        {
            text: "Name and Position",
            value: "name_and_position",
            sortable: true,
        },
        { text: "Company Name", value: "company_name", sortable: true },
        { text: "DBA", value: "dba", sortable: true },
        { text: "Day", value: "day", sortable: true },
        { text: "Month", value: "month", sortable: true },
        { text: "Year", value: "year", sortable: true },
    ]);

    /** Tally list */

    const headers_listing_tally = ref([
        {
            text: "Fulltime US workers",
            value: "fulltime_us_workers",
            sortable: true,
        },
        {
            text: "Parttime US workers",
            value: "parttime_us_workers",
            sortable: true,
        },
        {
            text: "Fulltime non-US workers",
            value: "fulltime_non_us_workers",
            sortable: true,
        },
        {
            text: "Parttime non-US workers",
            value: "parttime_non_us_workers",
            sortable: true,
        },
        {
            text: "Name and position",
            value: "name_and_position",
            sortable: true,
        },
        { text: "Year and Quarter", value: "year_and_quarter", sortable: true },

        { text: "Company Name", value: "company_name", sortable: true },
        { text: "DBA", value: "dba", sortable: true },
        { text: "Day", value: "day", sortable: true },
        { text: "Month", value: "month", sortable: true },
        { text: "Year", value: "year", sortable: true },
    ]);

    /** Edit employers */

    const headers_uploaded_plan_edit = ref([
        { text: "Category", value: "industry", sortable: true },
        { text: "Business Type", value: "businesstype", sortable: true },
        { text: "Report", value: "type", sortable: true },
        { text: "Year", value: "year", sortable: true },
        { text: "Quarter", value: "quarter", sortable: true },
        { text: "Document", value: "link", sortable: true },
        { text: "Action", value: "action", sortable: false },
    ]);

    const headers_plan_tally_edit = ref([
        {
            text: "Name and Position",
            value: "name_and_position",
            sortable: true,
        },
        { text: "Company Name", value: "company_name", sortable: true },
        { text: "DBA", value: "dba", sortable: true },
        { text: "Day", value: "day", sortable: true },
        { text: "Month", value: "month", sortable: true },
        { text: "Year", value: "year", sortable: true },
        { text: "Action", value: "action", sortable: false },
    ]);

    const headers_workforce_listing_edit = ref([
        { text: "Employee Name", value: "full_name", sortable: true },
        { text: "Major Soc Code", value: "major_soc_code", sortable: true },
        { text: "Minor Soc Code", value: "minor_soc_code", sortable: true },
        { text: "Position", value: "position", sortable: true },
        {
            text: "Employment Status",
            value: "employment_status",
            sortable: true,
        },
        { text: "Wage", value: "wage", sortable: true },
        {
            text: "Country of Citizenship",
            value: "country_of_citizenship",
            sortable: true,
        },
        {
            text: "VISA TYPE / CLASS",
            value: "visa_type_class",
            sortable: true,
        },
        {
            text: "Start Date of Employment",
            value: "employment_start_date",
            sortable: true,
        },
        {
            text: "End Date of Employment",
            value: "employment_end_date",
            sortable: true,
        },
        { text: "Action", value: "action", sortable: false },
    ]);

    const headers_listing_tally_edit = ref([
        {
            text: "Fulltime US workers",
            value: "fulltime_us_workers",
            sortable: true,
        },
        {
            text: "Parttime US workers",
            value: "parttime_us_workers",
            sortable: true,
        },
        {
            text: "Fulltime non-US workers",
            value: "fulltime_non_us_workers",
            sortable: true,
        },
        {
            text: "Parttime non-US workers",
            value: "parttime_non_us_workers",
            sortable: true,
        },
        {
            text: "Name and position",
            value: "name_and_position",
            sortable: true,
        },
        { text: "Year and Quarter", value: "year_and_quarter", sortable: true },

        { text: "Company Name", value: "company_name", sortable: true },
        { text: "DBA", value: "dba", sortable: true },
        { text: "Day", value: "day", sortable: true },
        { text: "Month", value: "month", sortable: true },
        { text: "Year", value: "year", sortable: true },
        { text: "Action", value: "action", sortable: false },
    ]);

    return {
        headers_uploaded_files,
        headers_uploaded_listing,
        headers_by_company,
        headers_uploaded_plan,

        headers_workforce_listing,
        headers_workforce_plan,

        headers_plan_tally,
        headers_plan_tally_edit,

        headers_workforce_plan_edit,
        headers_listing_tally,
        headers_listing_tally_edit,
        headers_workforce_listing_edit,
    };
}
