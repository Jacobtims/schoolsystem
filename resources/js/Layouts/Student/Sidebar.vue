<template>
    <div class="col-lg-3 col-xl-2 d-flex flex-column vh-100 flex-shrink-0 p-3" id="sidebar">
        <a class="text-decoration-none w-100 text-center text-dark">
            <h2 class="sidebar-title">{{ $page.props.app_name }}</h2>
        </a>
        <ul class="nav nav-pills flex-column mb-auto mt-4">
            <li class="nav-item mb-3">
                <Link :href="route('student.dashboard')" :class="{'nav-link': true, 'active': route().current('student.dashboard')}">
                    <i class="fa fa-home sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Dashboard</span>
                </Link>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa fa-first-order sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Agenda</span>
                </a>
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
<style>
#sidebar {
}

#sidebar .sidebar-icon {
    width: 18px;
    color: #575757;
}

#sidebar .sidebar-item {
    font-size: 17px;
    font-weight: 600;
    color: #575757;
}

#sidebar .nav-item .nav-link {
    border-radius: 10px;
}

#sidebar .nav-link.active,
#sidebar .nav-link:hover {
    background-color: #055CFC !important;
}
#sidebar .nav-link.active .sidebar-item,
#sidebar .nav-link.active .sidebar-icon,
#sidebar .nav-link:hover .sidebar-item,
#sidebar .nav-link:hover .sidebar-icon {
    color: #fff !important;
}

@media (max-width: 991.98px) {
    #sidebar .nav-link {
        text-align: center;
    }

    #sidebar .sidebar-icon {
        width: max-content;
        font-size: 25px;
    }

    #sidebar .sidebar-item {
        display: none;
    }

    #sidebar .sidebar-title {
        font-size: 18px;
    }

    #sidebar {
        max-width: 94px;
    }
}
</style>
