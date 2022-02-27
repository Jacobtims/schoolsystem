<template>
    <div id="admin-sidebar">
        <nav class="nav">
            <div>
                <!-- Sidebar logo -->
                <Link :href="route('admin.dashboard')" class="sidebar-logo">
                    <i class="fa-solid fa-s sidebar-logo-icon"></i>
                    <span class="sidebar-logo-name" id="sidebar-name">Admin Panel</span>
                </Link>

                <!-- Dashboard link -->
                <div class="sidebar-list" style="margin-top: 5rem;">
                    <Link :href="route('admin.dashboard')" :class="{'sidebar-link': true, 'active': isRoute('admin.dashboard')}">
                        <i class="fa fa-home sidebar-icon"></i>
                        <span class="sidebar-name">Dashboard</span>
                    </Link>
                </div>
                <!-- Gebruikers sidebar list -->
                <div class="sidebar-list mt-4">
                    <p class="sidebar-list-title">Gebruikers</p>
                    <!-- Studenten collapse -->
                    <a href="#collapseStudents" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseStudents" :class="{'sidebar-link': true, 'active': isRoute('admin.students.')}">
                        <i class="fa-solid fa-user-graduate sidebar-icon"></i><span class="sidebar-name">Studenten</span><i class="fa-solid fa-angle-down collapse-icon"></i>
                    </a>
                    <div class="collapse" id="collapseStudents">
                        <Link :href="route('admin.students.index')" :class="{'sidebar-link': true, 'sidebar-collapse-link': true, 'active': isRoute('admin.students.index')}">
                            <i class="fa-solid fa-user-graduate sidebar-icon"></i>
                            <span class="sidebar-name">Overzicht</span>
                        </Link>
                        <a href="#" class="sidebar-link sidebar-collapse-link">
                            <i class="fa-solid fa-user-graduate sidebar-icon"></i><span class="sidebar-name">Nieuwe toevoegen</span>
                        </a>
                    </div>
                    <!-- Docenten collapse -->
                    <a href="#collapseTeachers" class="sidebar-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseTeachers">
                        <i class="fa-solid fa-chalkboard-user sidebar-icon"></i><span class="sidebar-name">Docenten</span><i class="fa-solid fa-angle-down collapse-icon"></i>
                    </a>
                    <div class="collapse" id="collapseTeachers">
                        <a href="#" class="sidebar-link sidebar-collapse-link">
                            <i class="fa-solid fa-chalkboard-user sidebar-icon"></i><span class="sidebar-name">Overzicht</span>
                        </a>
                        <a href="#" class="sidebar-link sidebar-collapse-link">
                            <i class="fa-solid fa-chalkboard-user sidebar-icon"></i><span class="sidebar-name">Nieuwe toevoegen</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
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
            return this.currentRoute.startsWith(route);
        },
    }
}
</script>
<style lang="scss">
/* Only visible when sidebar is unfolded */
@media screen and (min-width: 768px) {
    #admin-sidebar.show,
    #admin-sidebar:hover {
        .nav {
            .sidebar-list {
                .sidebar-list-title {
                    display: block;
                    padding-left: 1.4rem;
                    text-transform: uppercase;
                    color: #c4c4c4;
                    font-size: 14px;
                    font-weight: 600;
                }
                .sidebar-collapse-link {
                    margin-bottom: 1rem;
                    display: grid;
                    &:first-child {
                        margin-top: -.5rem !important;
                    }
                    &:last-child {
                        margin-bottom: 1.5rem !important;
                    }
                }
                .collapse-icon {
                    display: inline;
                    font-size: 10px;
                    position: absolute;
                    right: 0;
                }
                .sidebar-name {
                    display: inline;
                }
            }
            .sidebar-logo {
                .sidebar-logo-name {
                    color: #fff;
                    font-weight: 700;
                    display: inline;
                }
            }
        }
    }
}

#admin-sidebar {
    position: fixed;
    top: 0;
    left: -30%;
    width: 68px;
    height: 100vh;
    background-color: #3B4B64;
    padding: .5rem 1rem 0 0;
    transition: .5s;
    z-index: 500;
    a {
        text-decoration: none;
    }
    .nav {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;

        .sidebar-list {
            .sidebar-logo,
            .sidebar-link {
                display: grid;
                grid-template-columns: max-content max-content;
                align-items: center;
                column-gap: 1rem;
                padding: .2rem 0 .2rem 1.5rem;
            }
            .sidebar-list-title {
                display: none;
            }
            .sidebar-name {
                display: none;
                font-size: 15px;
            }
            .sidebar-collapse-link .sidebar-icon {
                visibility: hidden;
            }
            .sidebar-collapse-link {
                display: none;
            }
            .collapse-icon {
                display: none;
            }
        }

        .sidebar-logo {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            margin-bottom: 2rem;
            font-size: 20px;
            padding: 1rem 1rem 1rem 1.5rem;
            background-color: #303C54;
            white-space: nowrap;
            overflow: hidden;

            .sidebar-logo-icon {
                font-size: 1.25rem;
                color: #fff;
                margin-right: 1rem;
            }
            .sidebar-logo-name {
                display: none;
            }
        }

        .sidebar-link {
            position: relative;
            color: #c4c4c4;
            margin-bottom: 1.5rem;
            transition: .3s;

            &:hover {
                transition: 0s;
                color: #fff;
            }

            &:hover::before {
                content: '';
                position: absolute;
                left: 0;
                width: 2px;
                height: 32px;
                background-color: #fff;
            }

            .sidebar-icon {
                font-size: 16px;
            }

            &.active {
                color: #fff;
            }
            &.active::before {
                content: '';
                position: absolute;
                left: 0;
                width: 2px;
                height: 32px;
                background-color: #fff;
            }
        }
    }
    &.show,
    &:hover{
        left: 0;
    }
    #sidebar-toggle {
        transition: .5s;
    }
}

@media screen and (min-width: 768px) {
    #admin-sidebar {
        left: 0;
        padding: 1rem 1rem 0 0;

        &.show,
        &:hover {
            width: calc(68px + 192px) !important;
        }
    }
}
</style>
