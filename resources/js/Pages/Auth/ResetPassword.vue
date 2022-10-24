<template>
    <Head title="Reset Password"/>

    <AuthLayout>
        <h1 class="text-3xl md:text-4xl text-dark-blue font-bold mb-5 md:mb-8">Wachtwoord opnieuw instellen</h1>

        <p class="mb-4">
            Vul hieronder je nieuwe wachtwoord in
        </p>

        <form @submit.prevent="submit" class="space-y-4 mb-6">
            <Input type="email" v-model="form.email" :invalid="form.errors.email" placeholder="E-mailadres" required/>

            <Input type="password" v-model="form.password" :invalid="form.errors.password" placeholder="Wachtwoord"
                   required autofocus/>

            <Input type="password" v-model="form.password_confirmation"
                   :invalid="form.errors.password_confirmation" placeholder="Bevestig wachtwoord"
                   required/>

            <Button :disabled="form.processing" btn="primary" class="w-full !mt-8">Wachtwoord opnieuw instellen</Button>
        </form>
    </AuthLayout>
</template>

<script>
import {Head, Link} from '@inertiajs/inertia-vue3';
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Input from "@/Components/Inputs/Input.vue";
import Button from "@/Components/Button.vue";
import Alert from "@/Components/Alert.vue";

export default {
    components: {
        AuthLayout,
        Head,
        Link,
        Input,
        Button,
        Alert
    },

    props: {
        email: String,
        token: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                token: this.token,
                email: this.email,
                password: '',
                password_confirmation: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.update'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>
