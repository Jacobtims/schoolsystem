<template>
    <div class="card">
        <div class="card-body p-4">
            <form @submit.prevent="updatePassword()">
                <Input label="Huidig wachtwoord" v-model="passwordForm.current_password" :error="passwordForm.errors.current_password" type="password" class="mb-3"/>
                <Input label="Nieuw wachtwoord" v-model="passwordForm.password" :error="passwordForm.errors.password" type="password" class="mb-3"/>
                <Input label="Bevestig wachtwoord" v-model="passwordForm.password_confirmation" :error="passwordForm.errors.password_confirmation" type="password" class="mb-4"/>

                <button class="btn btn-success" :disabled="passwordForm.processing"><i class="fa-solid fa-floppy-disk"></i> Opslaan</button>
            </form>
        </div>
    </div>
</template>
<script>
import Input from "@/Components/Inputs/Input.vue";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    components: {Input},
    data() {
        return {
            passwordForm: useForm({
                current_password: null,
                password: null,
                password_confirmation: null
            })
        }
    },
    methods: {
        updatePassword() {
            this.passwordForm.patch(route('user.profile.update-password'), {
                onSuccess: () => {
                    this.passwordForm.reset();
                    this.toast('success', 'Successvol!', 'Je wachtwoord is successvol gewijzigd.');
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegaan tijdens het wijzigen van je wachtwoord. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>
