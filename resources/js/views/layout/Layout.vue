<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="info">
            <b-navbar-brand href="/accounts">Account Manager</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav v-if="authUser">
                    <b-nav-item href="/accounts">My Accounts</b-nav-item>
                    <b-nav-item href="/">Transactions</b-nav-item>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item-dropdown text="Lang" right>
                        <b-dropdown-item href="#">EN</b-dropdown-item>
                        <b-dropdown-item href="#">AR</b-dropdown-item>
                    </b-nav-item-dropdown>
                    <template v-if="authUser">
                        <b-avatar></b-avatar>
                        <b-nav-item-dropdown right>
                            <!-- Using 'button-content' slot -->
                            <template v-slot:button-content>
                                <em>{{authUser.name}}</em>
                            </template>
                            <b-dropdown-item href="#" @click.prevent="logout">Sign Out</b-dropdown-item>
                        </b-nav-item-dropdown>
                    </template>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <router-view></router-view>
    </div>
</template>

<script>
    import {logout} from '@/auth';
    import axios from 'axios';

    export default {
        name: "Layout",
        methods: {
            logout(){
                axios({
                    method: 'post',
                    url: '/api/logout'
                }).then((response) => {
                    logout();
                    this.$router.push({name: 'Login'});
                })
            }
        }
    }
</script>

<style scoped>

</style>
