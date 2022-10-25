<template>
    <nav class="flex w-full items-center justify-between bg-white border-b border-gray-300 py-5 px-4 md:px-10">
        <div class="hidden md:block text-3xl font-semibold">
            {{ $page.props.settings.school_name }}
        </div>
        <button class="md:hidden bg-gray-100 rounded-lg w-[45px] h-[45px] z-[510]"
                @click="mobileNavbarOpen = !mobileNavbarOpen">
            <i class="fa-solid fa-bars text-xl" v-if="!mobileNavbarOpen"></i>
            <i class="fa-solid fa-xmark text-xl" v-else></i>
        </button>

        <div class="flex items-center">
            <button class="bg-gray-100 rounded-lg w-[45px] h-[45px] mr-6 hidden md:block">
                <i class="fa-regular fa-message text-xl"></i>
            </button>
            <button class="bg-gray-100 rounded-lg w-[45px] h-[45px] mr-10 hidden md:block">
                <i class="relative fa-regular fa-bell text-xl">
                    <span class="absolute -top-0 -right-1 bg-dark-red w-2.5 h-2.5 rounded-full"></span>
                </i>
            </button>
            <div class="relative inline-block w-[45px] h-[45px]">
                <button @click="dropdownOpen = !dropdownOpen">
                    <img :src="$page.props.auth.user.profile_photo_url" class="rounded-lg w-[45px] h-[45px]"/>
                </button>
                <div class="absolute right-0 z-10 w-52 bg-white rounded shadow-lg mt-2" v-if="dropdownOpen">
                    <ul class="py-1 text-sm text-gray-700 space-y-1">
                        <li>
                            <Link :href="route('user.profile.index')" class="block py-2 px-4 hover:bg-gray-100">
                                <i class="fa-regular fa-user w-5"></i> Profiel
                            </Link>
                        </li>
                        <li>
                            <Link class="block py-2 px-4 hover:bg-gray-100 w-full text-left" :href="route('logout')"
                                  method="post" as="button">
                                <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Uitloggen
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <MobileNavbar :show="mobileNavbarOpen"/>
</template>
<script>
import {Link} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import MobileNavbar from "@/Layouts/Student/MobileNavbar.vue";

export default {
    components: {MobileNavbar, Link},
    data() {
        return {
            dropdownOpen: false,
            mobileNavbarOpen: false,
        }
    },
    mounted() {
        Inertia.on('before', () => {
            this.dropdownOpen = false;
            this.mobileNavbarOpen = false;
        });
    },
}
</script>
