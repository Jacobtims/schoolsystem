<template>
    <aside class="hidden md:flex flex-col space-y-2 border-r border-gray-300 bg-white p2 ease-in-out duration-500"
           :class="isOpen ? 'w-80' : 'w-24'">
        <button class="flex items-center justify-center h-[86px] border-b border-gray-300" @click="toggleSidebar">
            <img alt="Logo"/>
        </button>

        <ul class="flex flex-col space-y-4 py-10 px-5 text-gray-700" :class="isOpen ? 'items-left' : 'items-center'">
            <li>
                <Link :href="route('student.dashboard')"
                      class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-100"
                      :class="isRoute('student.dashboard')">
                    <i class="fa-solid fa-house text-xl w-5"></i>
                    <span class="ml-3" :class="!isOpen && 'hidden'">Home</span>
                </Link>
            </li>
            <li>
                <Link :href="route('student.schedule')" class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-100"
                      :class="isRoute('student.schedule')">
                    <i class="fa-solid fa-calendar-week text-xl w-5"></i>
                    <span class="ml-3" :class="!isOpen && 'hidden'">Rooster</span>
                </Link>
            </li>
            <li>
                <Link :href="route('student.grades')" class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-100"
                      :class="isRoute('student.grades')">
                    <i class="fa-solid fa-list-ol text-xl w-5"></i>
                    <span class="ml-3" :class="!isOpen && 'hidden'">Cijfers</span>
                </Link>
            </li>
            <li>
                <Link :href="route('student.attendance')"
                      class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-100"
                      :class="isRoute('student.attendance')">
                    <i class="fa-regular fa-calendar-check text-xl w-5"></i>
                    <span class="ml-3" :class="!isOpen && 'hidden'">Presentie</span>
                </Link>
            </li>
        </ul>
    </aside>
</template>
<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link
    },
    data() {
        return {
            windowWidth: window.innerWidth,
            sidebarOpen: false
        }
    },
    computed: {
        isOpen() {
            return (this.windowWidth > 1024 && this.sidebarOpen);
        }
    },
    mounted() {
        if (localStorage.getItem('sidebarOpen') !== null) {
            this.sidebarOpen = JSON.parse(localStorage.getItem('sidebarOpen'));
        }
        window.onresize = () => {
            this.windowWidth = window.innerWidth
        }
    },
    methods: {
        isRoute(route) {
            return this.$page.props.current_route === route ? 'text-white bg-primary-500 hover:!bg-primary-500 !transition-none' : '';
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        }
    }
}
</script>
