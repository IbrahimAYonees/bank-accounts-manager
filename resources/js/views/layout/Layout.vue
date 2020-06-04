<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="dark">
            <b-navbar-brand href="/" :active="activePage === 'home_page'">Home</b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav v-if="authUser">
                    <b-nav-item href="/accounts" :active="activePage === 'Accounts'">My Accounts</b-nav-item>
<!--                    <b-nav-item href="/not_found" :active="activePage === 'Not_Found'">Transactions</b-nav-item>-->
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
<!--                    <b-nav-item-dropdown text="Lang" right>-->
<!--                        <b-dropdown-item href="#">EN</b-dropdown-item>-->
<!--                        <b-dropdown-item href="#">AR</b-dropdown-item>-->
<!--                    </b-nav-item-dropdown>-->
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
                    <b-button v-else href="/login">Login</b-button>
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
        computed: {
            activePage(){
                return this.$route.name;
            }
        },
        methods: {
            logout(){
                axios({
                    method: 'post',
                    url: '/api/logout'
                }).then((response) => {
                    logout();
                    this.$router.push({name: 'login'});
                })
            }
        }
    }
</script>

<style scoped>

</style>
