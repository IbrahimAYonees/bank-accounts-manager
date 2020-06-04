<template>
    <section class="container">
        <div class="row">
            <button class="offset-9 mt-3 btn btn-dark btn-md" @click.prevent="newAccount">New Account</button>
        </div>
        <b-overlay :show="showLoader" rounded="lg">
            <div class="pt-3" v-if="accounts && accounts.length" :aria-hidden="showLoader ? 'true' : null">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Bank</th>
                            <th>Number</th>
                            <th>Currency</th>
                            <th>Type</th>
                            <th>Branch</th>
                            <th>Date</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="account in accounts" :key="account.id">
                            <td>{{account.bank}}</td>
                            <td>{{account.number}}</td>
                            <td>{{account.currency}}</td>
                            <td>{{account.type}}</td>
                            <td>{{account.branch}}</td>
                            <td>{{account.date | calenderDate}}</td>
                            <td>{{account.active | active}}</td>
                            <td>
                                <button class="btn btn-dark btn-sm" @click.prevent="toggleActivateAccount(account)">
                                    {{account.active ? 'Deactivate' : 'activate'}}
                                </button>
                                <button class="btn btn-dark btn-sm" @click.prevent="editAccount(account)">
                                    Edit
                                    <b-icon-pencil-square></b-icon-pencil-square>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row justify-content-center" v-if="currentPage">
                    <b-pagination
                        v-model="currentPage"
                        :total-rows="rows"
                        :per-page="perPage"
                        aria-controls="my-table"
                    ></b-pagination>
                </div>
            </div>
        </b-overlay>
        <account-modal @changed="accountsChanged"></account-modal>
    </section>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import {BIconPencilSquare} from 'bootstrap-vue';
    import AccountModal from "./components/modals/AccountModal";

    export default {
        name: "AccountsPage",
        components: {
            BIconPencilSquare,
            AccountModal
        },
        computed: {
            showLoader(){
                return !(this.accounts && this.accounts.length);
            }
        },
        data() {
            return {
                accounts: null,
                currentPage: null,
                perPage: null,
                rows: null
            }
        },
        filters: {
            calenderDate(date){
                return moment(date).format('MMMM Do YYYY');
            },
            active(active){
                return active ? 'Yes' : 'no';
            }
        },
        mounted() {
            this.getAccounts();
        },
        methods: {
            fetchAccounts(page = 1) {
                return axios({
                    method: 'get',
                    url: '/api/accounts',
                    params: {
                        page
                    }
                });
            },
            activateAccount(id){
                return axios({
                    method: 'patch',
                    url: `/api/accounts/${id}/activate`
                })
            },
            deactivateAccount(id){
                return axios({
                    method: 'patch',
                    url: `/api/accounts/${id}/deactivate`
                })
            },
            getAccounts(first = false) {
                let page = first ? 1 : this.currentPage;
                this.accounts = null;
                this.fetchAccounts(page).then((response) => {
                    this.accounts = response.data.data;
                    this.currentPage = response.data.meta.current_page;
                    this.perPage = response.data.meta.per_page;
                    this.rows = response.data.meta.total;
                })
            },
            async toggleActivateAccount(account){
                let message,title;
                if(account.active){
                    await this.deactivateAccount(account.id);
                    message = 'Account Deactivated Successfully'
                    title = 'Deactivated'
                }else{
                    await this.activateAccount(account.id)
                    message = 'Account Activated Successfully';
                    title = 'Activated';
                }
                this.makeToast('success',title,message);
                await this.getAccounts(true);
            },
            newAccount(){
                this.$modal.show('account-modal');
            },
            editAccount(account){
                this.$modal.show('account-modal',{account})
            },
            async accountsChanged(action){
                let message,title;
                switch (action) {
                    case 'New':
                        message = 'Account Created Successfully';
                        title = 'Account Created';
                        break;
                    case 'Edit':
                        message = 'Account Edited Successfully';
                        title = 'Account Edited';
                        break;
                }
                await this.getAccounts(true);
                this.makeToast('success',title,message);
            },
            makeToast(variant,title,message) {
                this.$bvToast.toast(message, {
                    title: title,
                    variant: variant,
                    solid: true
                })
            }
        },
        watch: {
            currentPage(oldVal,newVal){
                if(oldVal !== newVal){
                    this.getAccounts();
                }
            }
        }
    }
</script>

<style scoped>

</style>
