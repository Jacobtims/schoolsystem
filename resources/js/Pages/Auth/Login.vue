<template>
    <Head title="Log in"/>

    <AuthLayout>
        <h1 class="text-5xl md:text-6xl text-dark-blue font-bold mb-8">Log in</h1>

        <Alert type="success" v-if="status">{{ status }}</Alert>

        <form @submit.prevent="submit" class="space-y-4">
            <Input type="email" v-model="form.email" :invalid="form.errors.email" placeholder="E-mailadres" required autofocus/>

            <Input type="password" v-model="form.password" :invalid="form.errors.password" placeholder="Wachtwoord" required/>

            <div class="flex items-center justify-between !mb-5">
                <Checkbox id="remember" v-model="form.remember">Onthoud me</Checkbox>
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-primary-600">
                    Wachtwoord vergeten?
                </Link>
            </div>

            <Button :disabled="form.processing" btn="primary" class="w-full">Inloggen</Button>
        </form>
    </AuthLayout>
</template>

<script>
import {Head, Link} from '@inertiajs/inertia-vue3';
import Input from "@/Components/Inputs/Input.vue";
import Button from "@/Components/Button.vue";
import Checkbox from "@/Components/Inputs/Checkbox.vue";
import Alert from "@/Components/Alert.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";

export default {
    components: {
        AuthLayout,
        Alert,
        Input,
        Head,
        Link,
        Button,
        Checkbox
    },

    props: {
        canResetPassword: Boolean,
        status: String
    },

    data() {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false
            }),
            currentYear: new Date().getFullYear()
        }
    },

    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    remember: this.form.remember ? 'on' : ''
                }))
                .post(this.route('login'), {
                    onFinish: () => this.form.reset('password'),
                })
        }
    }
}
</script>
