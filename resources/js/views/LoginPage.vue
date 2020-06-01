<template>
    <section class="login_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <form action="#">
                        <div>
                            <div>
                                <h3>Welcome Back</h3>
                                <p>You can sign in with your email</p>
                            </div>
                            <!-- end .login_header -->

                            <div>
                                <div v-if="invalidCredentials" class="alert alert-danger">The credentials you have
                                    entered are invalid
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="text" class="text_field"
                                           v-model.trim="email"
                                           placeholder="Enter your email...">
                                </div>

                                <div class="form-group">
                                    <label for="pass">Password</label>
                                    <input id="pass" type="password" class="text_field"
                                           v-model="password"
                                           placeholder="Enter your password...">
                                </div>


                                <button class="btn btn-md btn-round btn-primary" type="submit"
                                        :disabled="disabled"
                                        @click.prevent="login"
                                >Login Now
                                </button>

                            </div>
                            <!-- end .login--form -->
                        </div>
                        <!-- end .cardify -->
                    </form>
                </div>
                <!-- end .col-md-6 -->
            </div>
            <!-- end .row -->
        </div>
        <!-- end .container -->
    </section>

</template>

<script>
    import {login} from "@/auth";

    export default {
        name: "LoginPage",
        data() {
            return {
                email: null,
                password: null,
                invalidCredentials: false,
                disabled: false,
            };
        },
        mounted() {
        },
        methods: {
            login() {
                if (!this.email || !this.password) {
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

