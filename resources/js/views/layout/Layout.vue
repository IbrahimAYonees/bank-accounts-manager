<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="dark">
            <b-navbar-brand href="/" :active="activePage === 'home_page'">Home</b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav v-if="authUser">
                    <b-nav-item href="/accounts" :active="activePage === 'Accounts'">My Accounts</b-nav-item>
                    <b-nav-item href="/transactions" :active="activePage === 'transactions'">Transactions</b-nav-item>
                    <b-nav-item href="/deposits" :active="activePage === 'deposits'">Deposits</b-nav-item>
                    <b-nav-item href="/withdraws" :active="activePage === 'withdraws'">Withdraws</b-nav-item>
                    <b-nav-item href="/transfers" :active="activePage === 'transfers'">Transfers</b-nav-item>
                    <b-nav-item href="/transaction"
                                :active="activePage === 'transaction'"
                                v-if="activePage === 'transaction'"
                    >
                        New Transaction
                    </b-nav-item>
                </b-navbar-nav>

                <b-navbar-nav class="ml-auto">
<!--                    <b-nav-item-dropdown text="Lang" right>-->
<!--                        <b-dropdown-item href="#">EN</b-dropdown-item>-->
<!--                        <b-dropdown-item href="#">AR</b-dropdown-item>-->
<!--                    </b-nav-item-dropdown>-->
                    <b-nav-item href="/dashboard/analysis/bank_balance" :active="activePage === 'bank_analysis'">Dashboard</b-nav-item>
                    <template v-if="authUser">
                        <b-avatar></b-avatar>
                        <b-nav-item-dropdown right>
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
