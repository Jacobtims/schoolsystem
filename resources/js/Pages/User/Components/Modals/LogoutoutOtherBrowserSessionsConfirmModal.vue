<template>
    <FormModal :open="openModal" header="Uitloggen bij andere browsersessies" :disabled="logoutForm.processing" submit-text="Uitloggen bij andere browsersessies"
        @close="close" @action="logoutOtherBrowserSessions">
        <p>Vul je wachtwoord in om te bevestigen dat je wilt uitloggen bij alle andere browsersessies op al je apparaten.</p>

        <Input label="Wachtwoord" v-model="logoutForm.password" :error="logoutForm.errors.password" type="password"/>
    </FormModal>
</template>
<script>
import FormModal from "@/Components/Modals/FormModal.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Input from "@/Components/Inputs/Input.vue";

export default {
    components: {FormModal, Input},
    props: {
        openModal: Boolean
    },
    data() {
        return {
            logoutForm: useForm({
                password: null,
            })
        }
    },
    methods: {
        close() {
            this.$parent.openDeleteModal = false;
        },
        logoutOtherBrowserSessions() {
            this.logoutForm.delete(route('user.profile.logout-other-browser-sessions'), {
                onSuccess: () => {
                    this.close();
                    this.logoutForm.reset();
                    this.toast('success', 'Uitgelogd op alle andere apparaten!');
                },
                onError: () => {
                    this.toast('error', 'Er is iets fout gegaan!', 'Er is iets fout gegaan bij uitloggen bij andere browsersessies. Probeer het opnieuw.');
                }
            });
        }
    }
}
</script>
