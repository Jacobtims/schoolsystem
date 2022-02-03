<template>
    <Head title="Reset Password" />

    <main class="vh-100">
        <div class="container-fluid h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="d-md-block d-none col-md-6 col-lg-6 col-xl-8" id="left-side">
                    <div class="d-flex align-items-center h-100">
                        <section class="p-5">
                            <h1>Wachtwoord</h1>
                            <h3>opnieuw instellen...</h3>
                        </section>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 p-5 text-center">
                    <h3>Wachtwoord opnieuw instellen</h3>
                    <p class="mb-3">Vul hieronder je nieuwe wachtwoord in</p>

                    <form @submit.prevent="submit" class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="email" :class="{'form-control': true, 'is-invalid': form.errors.email}" id="email" v-model="form.email" required autofocus placeholder="Email address" aria-describedby="emailFeedback">
                                <label for="email">E-mailadres</label>

                                <div id="emailFeedback" class="invalid-feedback text-start" v-if="form.errors.email">
                                    {{ form.errors.email }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="password" :class="{'form-control': true, 'is-invalid': form.errors.password}" id="password" v-model="form.password" required autofocus placeholder="Wachtwoord" aria-describedby="passwordFeedback" autocomplete="new-password">
                                <label for="password">Wachtwoord</label>

                                <div id="passwordFeedback" class="invalid-feedback text-start" v-if="form.errors.password">
                                    {{ form.errors.password }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="password" :class="{'form-control': true, 'is-invalid': form.errors.password_confirmation}" id="password_confirmation" v-model="form.password_confirmation" required autofocus placeholder="Bevestig wachtwoord" aria-describedby="password_confirmationFeedback" autocomplete="new-password">
                                <label for="password_confirmation">Bevestig wachtwoord</label>

                                <div id="password_confirmationFeedback" class="invalid-feedback text-start" v-if="form.errors.password_confirmation">
                                    {{ form.errors.password_confirmation }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary mb-3 w-100 p-2" :disabled="form.processing">
                                Wachtwoord opnieuw instellen
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
            Link
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
<style>
#left-side {
    background: #5490ff;
    color: #fff;
    height: 100%;
}
</style>
