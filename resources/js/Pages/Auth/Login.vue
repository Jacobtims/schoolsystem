<template>
    <Head title="Login" />

    <main class="vh-100">
        <div class="container-fluid h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="d-md-block d-none col-md-6 col-lg-6 col-xl-8" id="left-side">
                    <div class="d-flex align-items-center h-100">
                        <section class="p-5">
                            <h1>Welkom terug!</h1>
                            <p class="mb-0">Voer je e-mailadres en</p>
                            <p>wachtwoord in om door te gaan</p>
                        </section>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 p-5 text-center">
                    <h1 class="mb-0" style="font-size: 50px;">Log in</h1>
                    <h3 class="mb-3">om toegang te krijgen tot het portaal</h3>

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
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="password" :class="{'form-control': true, 'is-invalid': form.errors.password}" id="password" v-model="form.password" required autocomplete="current-password" placeholder="Password" aria-describedby="passwordFeedback">
                                <label for="password" class="text-start">Wachtwoord</label>

                                <div id="passwordFeedback" class="invalid-feedback text-start" v-if="form.errors.password">
                                    {{ form.errors.password }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check text-start">
                                <input class="form-check-input" type="checkbox" id="remember" v-model="form.remember">
                                <label class="form-check-label" for="remember">Onthoud me</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary mb-3 w-100 p-2" :disabled="form.processing">
                                Inloggen
                            </button>
                        </div>

                        <div>
                            <Link v-if="canResetPassword" :href="route('password.request')">
                                Wachtwoord vergeten?
                            </Link>
                        </div>
                    </form>

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
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
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
