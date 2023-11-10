import { computed } from "vue";
export default function useJS() {
    const years = computed(() => {
        let _years = [];
        for (let year = 2020; year <= new Date().getFullYear() + 10; year++) {
            _years.push(year);
        }
        return _years;
    });

    const current_year = computed(() => {
        return new Date().getFullYear();
    });

    return {
        years,
        current_year
    };
}
