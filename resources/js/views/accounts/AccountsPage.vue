<template>
    <section class="container">
        <div class="row">
            <button class="offset-9 mt-3 btn btn-dark btn-md" @click.prevent="newAccount">New Account</button>
        </div>
        <div class="alert alert-info" v-if="noResults">
            No Results
        </div>
        <b-overlay :show="showLoader" rounded="lg" v-else>
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
                            <td>
                                <switches v-model="account.active"
                                          theme="bootstrap"
                                          color="primary"
                                          text-enabled="Active"
                                          text-disabled="Disabled"
                                          :emit-on-mount="false"
                                          @input="toggleActivateAccount(account)"></switches>
                            </td>
                            <td>
                                <button class="btn btn-dark btn-sm" @click.prevent="showOptions(account)">
                                    Options
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
        <account-options-modal></account-options-modal>
    </section>
</template>

<script>
    import {fetchAccounts,activateAccount,deactivateAccount} from "@/api";
    import moment from 'moment';
    import {BIconPencilSquare} from 'bootstrap-vue';
    import AccountModal from "./components/modals/AccountModal";
    import AccountOptionsModal from "./components/modals/AccountOptionsModal";
    import Switches from 'vue-switches';

    export default {
        name: "AccountsPage",
        components: {
            BIconPencilSquare,
            Switches,
            AccountModal,
            AccountOptionsModal
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
                rows: null,
                noResults: false
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
            getAccounts(first = false) {
                this.noResults = false;
                let page = first ? 1 : this.currentPage;
                this.accounts = null;
                fetchAccounts(page).then((response) => {
                    this.accounts = response.data.data;
                    this.noResults = this.accounts.length === 0;
                    this.currentPage = response.data.meta.current_page;
                    this.perPage = response.data.meta.per_page;
                    this.rows = response.data.meta.total;
                })
            },
            async toggleActivateAccount(account){
                let message,title;
                if(account.active){
                    await activateAccount(account.id);
                    message = 'Account Deactivated Successfully'
                    title = 'Deactivated'
                }else{
                    await deactivateAccount(account.id)
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
            showOptions(account){
                console.log('here');
                this.$modal.show('account-options-modal',{account});
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
    .clickable{
        cursor: pointer;
    }
</style>
