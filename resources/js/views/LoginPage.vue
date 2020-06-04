<template>
    <section class="container">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title mt-1">Login To Bank Accounts Manager</h3>
            </div>
            <hr>
            <div class="card-body">
                <div>
                    <h3>Welcome Back</h3>
                    <p>You can sign in with your email</p>
                </div>
                <div v-if="invalidCredentials" class="alert alert-danger">The credentials you have
                    entered are invalid
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text"
                           class="form-control"
                           :class="{'is-invalid': $v.email.$error}"
                           name="email"
                           id="email"
                           v-model="email"
                    >
                    <div class="text-danger invalid-feedback" style="display: block;"
                         v-if="validated && !$v.email.required"
                    >
                        you must enter your email
                    </div>
                    <div class="text-danger invalid-feedback" style="display: block;"
                         v-if="validated && !$v.email.email"
                    >
                        you must enter a valid email
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password"
                           class="form-control"
                           :class="{'is-invalid': $v.password.$error}"
                           name="password"
                           id="password"
                           v-model="password"
                    >
                    <div class="text-danger invalid-feedback" style="display: block;"
                         v-if="validated && !$v.password.required"
                    >
                        you must enter your password
                    </div>
                    <div class="text-danger invalid-feedback" style="display: block;"
                         v-if="validated && !$v.password.minLength"
                    >
                        password is at lest 6 characters
                    </div>
                </div>

                <div class="row justify-content-end m-0">
                    <button class="btn btn-dark btn-md new_user"
                            @click.prevent="login"
                            :disabled="disabled"
                    >
                        Login
                    </button>
                </div>
            </div>
        </div>
    </section>

</template>

<script>
    import {login} from "@/auth";
    import {required , minLength , email} from 'vuelidate/lib/validators';


    export default {
        name: "LoginPage",
        data() {
            return {
                email: null,
                password: null,
                invalidCredentials: false,
                disabled: false,
                validated: false,
            };
        },
        validations: {
            email: {
                required,
                email
            },
            password: {
                required,
                minLength: minLength(6)
            }
        },
        mounted() {
        },
        methods: {
            login() {
                this.$v.$touch();
                this.validated = true;
                if(this.$v.$invalid){
                    return;
                }
                this.disabled = true;
                login(this.email, this.password).then(() => {
                    this.$router.push({name: 'Accounts'});
                }).catch(() => {
                    this.invalidCredentials = true;
                }).finally(() => {
                    this.disabled = false;
                });
            }
        },
    }
</script>

<style scoped>

</style>

