<template>
    <nav class="navbar navbar-expand-sm navbar-light bg-white" id="admin-navbar">
        <div class="container-fluid">
            <div id="sidebar-toggle">
                <i class="fa-solid fa-bars" id="sidebar-toggle-icon" @click="showSidebar"></i>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item me-5">
                        <a class="nav-link notifications" href="#"><i class="fa-solid fa-bell"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <img :src="$page.props.auth.user.profile_photo_url"
                                 class="rounded-circle profile-image"/>
                            <span class="align-middle mx-2">{{ $page.props.auth.user.firstname }} {{ $page.props.auth.user.lastname }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <Link class="dropdown-item" :href="route('logout')" method="post" as="button" type="button">Uitloggen</Link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {Link},
    methods: {
        showSidebar() {
            const nav = document.getElementById("admin-sidebar"),
                  bodypd = document.getElementById("admin-main"),
                  headerpd = document.getElementById("sidebar-toggle"),
                  toggleicon = document.getElementById("sidebar-toggle-icon");
            if(nav && bodypd && headerpd){
                // Show navbar
                nav.classList.toggle('show');
                // Add padding to body
                bodypd.classList.toggle('body-pd');
                // Add padding to header
                headerpd.classList.toggle('header-pd');
                // Change icon
                toggleicon.classList.toggle('fa-xmark');
            }
        }
    }
}
</script>
<style lang="scss">
#admin-navbar {
    border-bottom: 1px solid #D3D3D3;
    position: sticky;
    top: 0;
    z-index: 450;

    .nav-link {
        font-size: 17px;
        font-weight: 500;
    }
    .notifications {
        font-size: 24px;
        padding: 4px 8px 4px 8px;
    }
    .profile-image {
        width: 30px;
        height: 30px;
    }
    .dropdown-toggle::after {
        vertical-align: middle;
    }
    #sidebar-toggle-icon {
        color: #4C4C4C;
        font-size: 1.5rem;
        cursor: pointer;
    }
    .header-pd {
        padding-left: calc(68px + 16px);
    }
    #sidebar-toggle {
        transition: .5s;
    }
}

@media screen and (min-width: 768px) {
    #admin-navbar {
        #sidebar-toggle {
            //height: calc(3rem + 1rem);
            padding: 0 2rem 0 72px;
        }
        .header-pd {
            padding-left: calc(260px /* Width of sidebar */ + 16px - 12px /* Navbar padding */) !important;
        }
    }
}
</style>
