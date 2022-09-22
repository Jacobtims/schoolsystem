<template>
    <div class="card">
        <div class="card-body p-4">
            <p>
                Wanneer het nodig is kan je op alle apparaten uitloggen bij al de browsersessies.
                De recente sessies staan hieronder; deze lijst is mogelijk niet volledig. <br/>
                Als je denkt dat je account gehackt is, moet je ook je wachtwoord veranderen!
            </p>
            <!-- Other Browser Sessions -->
            <div v-if="sessions.length > 0" class="mt-5">
                <div v-for="(session, i) in sessions" :key="i" class="d-flex align-items-center mb-4">
                    <div class="session-icon text-center">
                        <i class="fa-solid fa-desktop" v-if="session.agent.is_desktop"></i>
                        <i class="fa-solid fa-mobile-screen-button" v-else></i>
                    </div>

                    <div class="ms-3">
                        <div class="fw-bold">
                            {{ session.agent.platform ? session.agent.platform : 'Unknown' }} - {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
                        </div>

                        <div>
                            <div class="small">
                                {{ session.ip_address }},

                                <span v-if="session.is_current_device" class="text-success">Dit apparaat</span>
                                <span v-else>Laatst actief {{ session.last_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-secondary" @click="logoutOtherBrowserSessions">Uitloggen bij andere browsersessies</button>
        </div>
    </div>

    <!-- Modals -->
    <logoutout-other-browser-sessions-confirm-modal :open-modal="openDeleteModal"/>
</template>
<script>
import LogoutoutOtherBrowserSessionsConfirmModal
    from "@/Pages/User/Components/Modals/LogoutoutOtherBrowserSessionsConfirmModal.vue";

export default {
    components: {LogoutoutOtherBrowserSessionsConfirmModal},
    props: {
        sessions: Array
    },
    data() {
        return {
            openDeleteModal: false
        }
    },
    methods: {
        logoutOtherBrowserSessions() {
            this.openDeleteModal = true;
        }
    }
}
</script>
<style lang="scss">
.session-icon {
    width: 36px;
    i {
        font-size: 30px;
        color: #494949;
    }
}
</style>
