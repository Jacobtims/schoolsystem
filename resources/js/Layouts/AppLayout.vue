<!--<template>-->
<!--    <div>-->
<!--        <Head :title="title" />-->

<!--        <jet-banner />-->

<!--        <div class="min-h-screen bg-gray-100">-->
<!--            <nav class="bg-white border-b border-gray-100">-->
<!--                &lt;!&ndash; Primary Navigation Menu &ndash;&gt;-->
<!--                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">-->
<!--                    <div class="flex justify-between h-16">-->
<!--                        <div class="flex">-->
<!--                            &lt;!&ndash; Logo &ndash;&gt;-->
<!--                            <div class="shrink-0 flex items-center">-->
<!--                                <Link :href="route('dashboard')">-->
<!--                                    <jet-application-mark class="block h-9 w-auto" />-->
<!--                                </Link>-->
<!--                            </div>-->

<!--                            &lt;!&ndash; Navigation Links &ndash;&gt;-->
<!--                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">-->
<!--                                <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">-->
<!--                                    Dashboard-->
<!--                                </jet-nav-link>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        <div class="hidden sm:flex sm:items-center sm:ml-6">-->
<!--                            &lt;!&ndash; Settings Dropdown &ndash;&gt;-->
<!--                            <div class="ml-3 relative">-->
<!--                                <jet-dropdown align="right" width="48">-->
<!--                                    <template #trigger>-->
<!--                                        <span class="inline-flex rounded-md">-->
<!--                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">-->
<!--                                                {{ $page.props.auth.user.name }}-->

<!--                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">-->
<!--                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />-->
<!--                                                </svg>-->
<!--                                            </button>-->
<!--                                        </span>-->
<!--                                    </template>-->

<!--                                    <template #content>-->
<!--                                        &lt;!&ndash; Account Management &ndash;&gt;-->
<!--                                        <div class="block px-4 py-2 text-xs text-gray-400">-->
<!--                                            Manage Account-->
<!--                                        </div>-->

<!--&lt;!&ndash;                                        <jet-dropdown-link :href="route('profile.show')">&ndash;&gt;-->
<!--&lt;!&ndash;                                            Profile&ndash;&gt;-->
<!--&lt;!&ndash;                                        </jet-dropdown-link>&ndash;&gt;-->

<!--&lt;!&ndash;                                        <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">&ndash;&gt;-->
<!--&lt;!&ndash;                                            API Tokens&ndash;&gt;-->
<!--&lt;!&ndash;                                        </jet-dropdown-link>&ndash;&gt;-->

<!--                                        <div class="border-t border-gray-100"></div>-->

<!--                                        &lt;!&ndash; Authentication &ndash;&gt;-->
<!--                                        <form @submit.prevent="logout">-->
<!--                                            <jet-dropdown-link as="button">-->
<!--                                                Log Out-->
<!--                                            </jet-dropdown-link>-->
<!--                                        </form>-->
<!--                                    </template>-->
<!--                                </jet-dropdown>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        &lt;!&ndash; Hamburger &ndash;&gt;-->
<!--                        <div class="-mr-2 flex items-center sm:hidden">-->
<!--                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">-->
<!--                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">-->
<!--                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />-->
<!--                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />-->
<!--                                </svg>-->
<!--                            </button>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

<!--                &lt;!&ndash; Responsive Navigation Menu &ndash;&gt;-->
<!--                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">-->
<!--                    <div class="pt-2 pb-3 space-y-1">-->
<!--                        <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">-->
<!--                            Dashboard-->
<!--                        </jet-responsive-nav-link>-->
<!--                    </div>-->

<!--                    &lt;!&ndash; Responsive Settings Options &ndash;&gt;-->
<!--                    <div class="pt-4 pb-1 border-t border-gray-200">-->
<!--                        <div class="flex items-center px-4">-->
<!--                            <div>-->
<!--                                <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>-->
<!--                                <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        <div class="mt-3 space-y-1">-->
<!--&lt;!&ndash;                            <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">&ndash;&gt;-->
<!--&lt;!&ndash;                                Profile&ndash;&gt;-->
<!--&lt;!&ndash;                            </jet-responsive-nav-link>&ndash;&gt;-->

<!--&lt;!&ndash;                            <jet-responsive-nav-link :href="route('api-tokens.index')" :active="route().current('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">&ndash;&gt;-->
<!--&lt;!&ndash;                                API Tokens&ndash;&gt;-->
<!--&lt;!&ndash;                            </jet-responsive-nav-link>&ndash;&gt;-->

<!--                            &lt;!&ndash; Authentication &ndash;&gt;-->
<!--                            <form method="POST" @submit.prevent="logout">-->
<!--                                <jet-responsive-nav-link as="button">-->
<!--                                    Log Out-->
<!--                                </jet-responsive-nav-link>-->
<!--                            </form>-->

<!--                            &lt;!&ndash; Team Management &ndash;&gt;-->
<!--&lt;!&ndash;                            <template v-if="$page.props.jetstream.hasTeamFeatures">&ndash;&gt;-->
<!--&lt;!&ndash;                                <div class="border-t border-gray-200"></div>&ndash;&gt;-->

<!--&lt;!&ndash;                                <div class="block px-4 py-2 text-xs text-gray-400">&ndash;&gt;-->
<!--&lt;!&ndash;                                    Manage Team&ndash;&gt;-->
<!--&lt;!&ndash;                                </div>&ndash;&gt;-->

<!--&lt;!&ndash;                                &lt;!&ndash; Team Settings &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                <jet-responsive-nav-link :href="route('teams.show', $page.props.auth.user.current_team)" :active="route().current('teams.show')">&ndash;&gt;-->
<!--&lt;!&ndash;                                    Team Settings&ndash;&gt;-->
<!--&lt;!&ndash;                                </jet-responsive-nav-link>&ndash;&gt;-->

<!--&lt;!&ndash;                                <jet-responsive-nav-link :href="route('teams.create')" :active="route().current('teams.create')" v-if="$page.props.jetstream.canCreateTeams">&ndash;&gt;-->
<!--&lt;!&ndash;                                    Create New Team&ndash;&gt;-->
<!--&lt;!&ndash;                                </jet-responsive-nav-link>&ndash;&gt;-->

<!--&lt;!&ndash;                                <div class="border-t border-gray-200"></div>&ndash;&gt;-->

<!--&lt;!&ndash;                                &lt;!&ndash; Team Switcher &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                <div class="block px-4 py-2 text-xs text-gray-400">&ndash;&gt;-->
<!--&lt;!&ndash;                                    Switch Teams&ndash;&gt;-->
<!--&lt;!&ndash;                                </div>&ndash;&gt;-->

<!--&lt;!&ndash;                                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">&ndash;&gt;-->
<!--&lt;!&ndash;                                    <form @submit.prevent="switchToTeam(team)">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <jet-responsive-nav-link as="button">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="flex items-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <svg v-if="team.id == $page.props.auth.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div>{{ team.name }}</div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        </jet-responsive-nav-link>&ndash;&gt;-->
<!--&lt;!&ndash;                                    </form>&ndash;&gt;-->
<!--&lt;!&ndash;                                </template>&ndash;&gt;-->
<!--&lt;!&ndash;                            </template>&ndash;&gt;-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </nav>-->

<!--            &lt;!&ndash; Page Heading &ndash;&gt;-->
<!--            <header class="bg-white shadow" v-if="$slots.header">-->
<!--                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">-->
<!--                    <slot name="header"></slot>-->
<!--                </div>-->
<!--            </header>-->

<!--            &lt;!&ndash; Page Content &ndash;&gt;-->
<!--            <main>-->
<!--                <slot></slot>-->
<!--            </main>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<!--<script>-->
<!--    import { defineComponent } from 'vue'-->
<!--    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'-->
<!--    import JetBanner from '@/Jetstream/Banner.vue'-->
<!--    import JetDropdown from '@/Jetstream/Dropdown.vue'-->
<!--    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'-->
<!--    import JetNavLink from '@/Jetstream/NavLink.vue'-->
<!--    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'-->
<!--    import { Head, Link } from '@inertiajs/inertia-vue3';-->

<!--    export default defineComponent({-->
<!--        props: {-->
<!--            title: String,-->
<!--        },-->

<!--        components: {-->
<!--            Head,-->
<!--            JetApplicationMark,-->
<!--            JetBanner,-->
<!--            JetDropdown,-->
<!--            JetDropdownLink,-->
<!--            JetNavLink,-->
<!--            JetResponsiveNavLink,-->
<!--            Link,-->
<!--        },-->

<!--        data() {-->
<!--            return {-->
<!--                showingNavigationDropdown: false,-->
<!--            }-->
<!--        },-->

<!--        methods: {-->
<!--            switchToTeam(team) {-->
<!--                this.$inertia.put(route('current-team.update'), {-->
<!--                    'team_id': team.id-->
<!--                }, {-->
<!--                    preserveState: false-->
<!--                })-->
<!--            },-->

<!--            logout() {-->
<!--                this.$inertia.post(route('logout'));-->
<!--            },-->
<!--        }-->
<!--    })-->
<!--</script>-->
