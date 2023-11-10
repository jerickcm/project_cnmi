<script>
import { Link } from "@inertiajs/vue3";
import BreezeApplicationLogo from "@/Components/ApplicationLogoLanding.vue";
export default {
    components: {
        BreezeApplicationLogo,
        Link,
    },
    data: () => ({
        appname: "",
        subtitle: "CNMI Department of Labor Reports",
    }),
};
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-r from-blue-600 via-blue-400 to-blue-700 shadow-inner"
    >
        <div class="flex flex-items items-center">
            <div class="">
                <BreezeApplicationLogo class="block h-20 w-auto" />
            </div>
            <div class="ml-2">
                <Link href="/login">
                    <h1 class="text-2xl text-white font-bold">
                        Commonwealth of the Northern <br />
                        Mariana Island <label class="text-xl">(CNMI)</label>
                    </h1>
                    <p class="text-md text-white font-bold">{{ subtitle }}</p>
                </Link>
            </div>
        </div>

        <div class="w-full sm:max-w-md px-2">
            <div
                class="w-full sm:max-w-md bg-white shadow-md overflow-hidden rounded-lg sm:rounded-lg mt-6 px-6 py-4"
            >
                <slot />
                <br />
                <br />
                <p class="text-xs">
                    To know your login credentials, or create a new login,
                    please contact CNMI DOL at
                    <a href="mailto:info@cnmidol.gov.mp" class="text-sky-500"
                        >info@cnmidol.gov.mp </a
                    >or call 670-323-9995.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.bg-page {
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
</style>
