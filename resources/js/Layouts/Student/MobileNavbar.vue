<template>
    <div class="h-full w-0 fixed top-0 left-0 overflow-x-hidden bg-white duration-500" :class="show && '!w-full'">
        <nav class="px-4 mt-14">
            <ul class="flex flex-col items-left space-y-4 py-10 text-gray-700">
                <li>
                    <Link :href="route('student.dashboard')" class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-100" :class="isRoute('student.dashboard')">
                        <i class="fa-solid fa-house text-xl w-5"></i>
                        <span class="ml-3">Home</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('student.schedule')" class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-100" :class="isRoute('student.schedule')">
                        <i class="fa-solid fa-calendar-week text-xl w-5"></i>
                        <span class="ml-3">Rooster</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('student.grades')" class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-100" :class="isRoute('student.grades')">
                        <i class="fa-solid fa-list-ol text-xl w-5"></i>
                        <span class="ml-3">Cijfers</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('student.attendance')" class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-100" :class="isRoute('student.attendance')">
                        <i class="fa-regular fa-calendar-check text-xl w-5"></i>
                        <span class="ml-3">Presentie</span>
                    </Link>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {Link},
    props: {
        show: {
            default: false,
            type: Boolean
        }
    },
    methods: {
        isRoute(route) {
            return this.$page.props.current_route === route ? 'text-white bg-primary-500 hover:!bg-primary-500 !transition-none' : '';
        },
    }
}
</script>
