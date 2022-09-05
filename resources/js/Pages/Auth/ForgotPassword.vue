<template>
    <Head title="Forgot Password" />

    <main class="vh-100">
        <div class="container-fluid h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="d-md-block d-none col-md-6 col-lg-6 col-xl-8" id="left-side">
                    <div class="d-flex align-items-center h-100">
                        <section class="p-5">
                            <h1>Wachtwoord vergeten?</h1>
                            <h4>Geen probleem!</h4>
                        </section>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 p-5 text-center">
                    <h3>Je wachtwoord vergeten? Geen probleem.</h3>
                    <p class="mb-3">
                        Laat ons je e-mailadres weten en we zullen je een link sturen voor het opnieuw instellen van je wachtwoord.
                    </p>

                    <div v-if="status" class="alert alert-success">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="email" :class="{'form-control': true, 'is-invalid': form.errors.email}" id="email" v-model="form.email" required autofocus placeholder="Email address" aria-describedby="emailFeedback">
                                <label for="email" class="text-start">E-mailadres</label>

                                <div id="emailFeedback" class="invalid-feedback text-start" v-if="form.errors.email">
                                    {{ form.errors.email }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary mb-3 w-100 p-2" :disabled="form.processing">
                                E-mail Wachtwoord Reset Link
                            </button>
                        </div>
                    </form>

                    <div class="text-start">
                        <Link :href="route('login')" as="button" type="button" class="btn btn-secondary">
                            <i class="fa-solid fa-angle-left"></i> Log in
                        </Link>
                    </div>

                    <div class="d-none">
                        Copyright &copy; {{ currentYear }} - Jacob Timmerman
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        Head,
        Link,
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
<style>
#left-side {
    background: #5490ff;
    color: #fff;
    height: 100%;
}
</style>
