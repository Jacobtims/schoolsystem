<template>
    <div class="col-lg-3 col-xl-2 d-flex flex-column vh-100 flex-shrink-0 p-3 bg-white" id="sidebar">
        <a class="text-decoration-none w-100 text-center text-dark">
            <h2 class="sidebar-title">Admin panel</h2>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <Link :href="route('admin.dashboard')" :class="{'nav-link': true, 'active': route().current('admin.dashboard')}">
                    <i class="fa fa-home sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Dashboard</span>
                </Link>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa fa-first-order sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">My Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa fa-cog sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Settings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa fa-bookmark sidebar-icon"></i>
                    <span class="ms-2 sidebar-item">Bookmarks</span>
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
    border-right: 1px solid #676565;
}

.sidebar-icon {
    width: 18px;
    color: #575757;
}

.sidebar-item {
    font-size: 17px;
    font-weight: 600;
    color: #575757;
}

.nav-link.active {
    background-color: #F4F9FF !important;
}
.nav-link.active .sidebar-item, .nav-link.active .sidebar-icon {
    color: #055CFC !important;
}

@media (max-width: 991.98px) {
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

    #sidebar {
        max-width: 94px;
    }
}
</style>
