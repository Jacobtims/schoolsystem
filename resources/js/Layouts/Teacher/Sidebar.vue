<template>
    <div class="col-lg-3 col-xl-2 d-flex flex-column vh-100 flex-shrink-0 p-3" id="student-sidebar">
        <a class="text-decoration-none w-100 text-center text-dark">
            <Link :href="route('teacher.dashboard')" class="text-decoration-none text-black">
                <h2 class="sidebar-title">{{ $page.props.settings.school_name }}</h2>
            </Link>
        </a>
        <ul class="nav nav-pills flex-column mb-auto mt-4">
            <li class="nav-item mb-3">
                <Link :href="route('teacher.dashboard')" :class="{'nav-link': true, 'active': isRoute('teacher.dashboard')}">
                    <i class="fa fa-home sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Dashboard</span>
                </Link>
            </li>

            <li class="nav-item mb-3">
                <Link :href="route('teacher.schedule')" :class="{'nav-link': true, 'active': isRoute('teacher.schedule')}">
                    <i class="fa-solid fa-calendar sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Rooster</span>
                </Link>
            </li>
            <li class="nav-item mb-3">
                <Link :href="route('teacher.attendance')" :class="{'nav-link': true, 'active': isRoute('teacher.attendance')}">
                    <i class="fa-solid fa-circle-check sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Registratie</span>
                </Link>
            </li>
            <li class="nav-item mb-3">
                <Link :href="route('teacher.grades')" :class="{'nav-link': true, 'active': isRoute('teacher.grades')}">
                    <i class="fa-solid fa-list-ol sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Cijfers</span>
                </Link>
            </li>
        </ul>
    </div>
</template>
<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link
    },
    computed: {
        currentRoute() {
            return this.$page.props.current_route;
        },
    },
    methods: {
        isRoute(route) {
            return this.currentRoute == route;
        },
    }
}
</script>
<style lang="scss">
#student-sidebar {
    .sidebar-icon {
        width: 18px;
        color: #575757;
    }
    .sidebar-item {
        font-size: 17px;
        font-weight: 600;
        color: #575757;
    }
    .nav-item .nav-link {
        border-radius: 10px;

        &.active,
        &:hover {
            background-color: #055CFC !important;
        }

        &.active .sidebar-item,
        &.active .sidebar-icon,
        &:hover .sidebar-item,
        &:hover .sidebar-icon {
            color: #fff !important;
        }
    }
}

@media (max-width: 991.98px) {
    #student-sidebar {
        max-width: 94px;

        .nav-link {
            text-align: center;
        }
        .sidebar-icon {
            width: max-content;
            font-size: 25px;
        }
        .sidebar-item {
            display: none;
        }
        .sidebar-title {
            font-size: 18px;
        }
    }
}
</style>
