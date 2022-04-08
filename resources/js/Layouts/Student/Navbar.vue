<template>
    <nav class="navbar navbar-expand-sm navbar-light" id="student-navbar">
        <div class="container-fluid">
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
                            <img :src="'https://eu.ui-avatars.com/api/?size=30&name='+$page.props.user.firstname+'+'+$page.props.user.lastname"
                                 class="rounded-circle profile-image"/>
                            <span class="align-middle mx-2">{{ $page.props.user.firstname }} {{ $page.props.user.lastname }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <Link class="dropdown-item" :href="route('user.settings')">
                                Instellingen
                            </Link>
                            <hr class="mb-0 mt-2"/>
                            <Link class="dropdown-item" @click="logout">
                                Uitloggen
                            </Link>
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
    methods: {
        logout() {
            this.$inertia.post(route('logout'));
        }
    },
    components: {
        Link
    }
}
</script>
<style lang="scss">
#student-navbar {
    margin: 0 -12px;

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
}
</style>
