import axios from "axios";
import { ref, onMounted, reactive, watch, computed } from "vue";

export default function useWatch() {
    watch(
        () => detail.value.businesses_id,
        (value) => {
            filteredType(value);
        }
    );

    return {
        filteredType,
        detail,
    };
}
