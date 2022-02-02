<template>
    <Head title="Login" />

<!--    <jet-validation-errors class="mb-4" />-->

<!--    <div v-if="status">-->
<!--        {{ status }}-->
<!--    </div>-->

    <main class="vh-100">
        <div class="container-fluid h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="d-md-block d-none col-md-6 col-lg-7 col-xl-8" id="left-side">
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <section>
                            <h1>Welcome back!</h1>
                            <p class="mb-0">Enter your e-mail and</p>
                            <p>Password to continue</p>
                        </section>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-5 col-xl-4 p-5 text-center">
                    <h1 class="mb-0">Log in</h1>
                    <h2 class="mb-3">to access the portal</h2>

                    <form @submit.prevent="submit" class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" v-model="form.email" required autofocus placeholder="Email address">
                                <label for="email">Email address</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" v-model="form.password" required autocomplete="current-password" placeholder="Password">
                                <label for="password">Password</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check text-start">
                                <input class="form-check-input" type="checkbox" id="remember" v-model="form.remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary mb-3 w-100 p-2" :disabled="form.processing">
                                Login
                            </button>
                        </div>

                        <div>
                            <Link v-if="canResetPassword" :href="route('password.request')">
                                Forgot your password?
                            </Link>
                        </div>
                    </form>

                    <div class="mt-5">
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
