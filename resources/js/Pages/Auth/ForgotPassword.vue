<template>
    <Head title="Forgot Password" />

    <AuthLayout>
        <h1 class="text-3xl md:text-4xl text-dark-blue font-bold mb-1">Wachtwoord vergeten?</h1>
        <h2 class="text-lg md:text-xl text-dark-blue font-semibold mb-5 md:mb-8">Geen probleem!</h2>

        <p class="mb-4">
            Laat ons je e-mailadres weten en we zullen je een link sturen om je wachtwoord opnieuw in te stellen.
        </p>

        <Alert type="success" v-if="status">{{ status }}</Alert>

        <form @submit.prevent="submit" class="space-y-6 mb-6">
            <Input type="email" v-model="form.email" :invalid="form.errors.email" placeholder="E-mailadres" required autofocus/>

            <Button :disabled="form.processing" btn="primary" class="w-full">E-mail Wachtwoord Reset Link</Button>
        </form>

        <Link :href="route('login')" class="text-primary-600">
            <i class="fa-solid fa-angle-left"></i> Log in
        </Link>
    </AuthLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
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
        status: String
    },

    data() {
        return {
            form: this.$inertia.form({
                email: ''
            }),
            currentYear: new Date().getFullYear()
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.email'))
        }
    }
}
</script>
