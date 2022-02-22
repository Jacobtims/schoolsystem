<template>
    <div class="col-lg-3 col-xl-2 d-flex flex-column vh-100 flex-shrink-0 p-3" id="student-sidebar">
        <a class="text-decoration-none w-100 text-center text-dark">
            <Link :href="route('student.dashboard')" class="text-decoration-none text-black">
                <h2 class="sidebar-title">{{ $page.props.settings.school_name }}</h2>
            </Link>
        </a>
        <ul class="nav nav-pills flex-column mb-auto mt-4">
            <li class="nav-item mb-3">
                <Link :href="route('student.dashboard')" :class="{'nav-link': true, 'active': route().current('student.dashboard')}">
                    <i class="fa fa-home sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Dashboard</span>
                </Link>
            </li>
<!--            <li class="nav-item mb-3">-->
<!--                <a href="#" class="nav-link">-->
<!--                    <i class="fa-solid fa-book sidebar-icon"></i>-->
<!--                    <span class="ms-2 sidebar-item">Agenda</span>-->
<!--                </a>-->
<!--            </li>-->
            <li class="nav-item mb-3">
                <Link :href="route('student.schedule')" :class="{'nav-link': true, 'active': route().current('student.schedule')}">
                    <i class="fa-solid fa-calendar sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Rooster</span>
                </Link>
            </li>
            <li class="nav-item mb-3">
                <Link :href="route('student.grades')" :class="{'nav-link': true, 'active': route().current('student.grades')}">
                    <i class="fa-solid fa-list-ol sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Cijfers</span>
                </Link>
            </li>
            <li class="nav-item mb-3">
                <Link :href="route('student.attendance')" :class="{'nav-link': true, 'active': route().current('student.attendance')}">
                    <i class="fa-solid fa-check sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Presentie</span>
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
