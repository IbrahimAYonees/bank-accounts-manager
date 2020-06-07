<template>
    <section class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Filters</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div id="bank-form" class="form-group col-4">
                        <label for="bank">Bank</label>
                        <multiselect :options="banks"
                                     :searchable="true"
                                     id="bank"
                                     :close-on-select="true"
                                     track-by="id"
                                     :show-labels="true"
                                     :hide-selected="false"
                                     placeholder="choose a bank"
                                     label="name"
                                     :internal-search="true"
                                     v-model="bank"
                                     @select="updateBank"
                                     @remove="removeBank"
                        >
                        </multiselect>
                    </div>
                    <div class="form-group col-4">
                        <label for="account_number">Account Number</label>
                        <input type="text"
                               class="form-control"
                               name="branch"
                               id="account_number"
                               v-model="filters.account_number"
                               placeholder="enter account number"
                        >
                    </div>
                    <div class="form-group col-4">
                        <label for="date">Date Range</label>
                        <flat-pickr
                            id="date"
                            class="form-control"
                            v-model="date"
                            :config="dateConfig"
                            placeholder="chose a date range"
                        ></flat-pickr>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-md btn-light offset-8" @click.prevent="clearFilters">Clear All Filters</button>
                <button class="btn btn-md btn-dark" @click.prevent="applyFilters">Apply Filters</button>
            </div>
        </div>
        <div class="alert alert-info" v-if="noResults">
            No Results
        </div>
        <b-overlay v-else :show="showLoader" rounded="lg">
            <div class="pt-3" v-if="transactions && transactions.length" :aria-hidden="showLoader ? 'true' : null">
                <div class="card mb-3" v-for="transaction in transactions" :key="transaction.id">
                    <div class="card-header">
                        <h6 class="card-title">
                            {{transaction.date | calenderDate}}
                        </h6>
                    </div>
                    <div class="card-body">
                        <template v-for="transfer in transaction.transfers">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>From</th>
                                    <th>Account Balance</th>
                                    <th v-if="transfer.canBeCancel">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        {{transfer.date | numbersDate}}
                                    </td>
                                    <td>
                                        {{transaction.bank}} {{transaction.account_number}}
                                    </td>
                                    <td>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Amount</th>
                                                <th>CUR</th>
                                                <th>Rate</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{transfer.amount}}</td>
                                                <td>{{transfer.currency}}</td>
                                                <td>{{transfer.rate}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>To</th>
                                                <th>Amount</th>
                                                <th>CUR</th>
                                                <th>Rate</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    {{transfer.to_bank}} {{transfer.to_account}}
                                                </td>
                                                <td>{{transfer.balance_amount}}</td>
                                                <td>{{transaction.account_currency}}</td>
                                                <td>{{transfer.account_rate}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td v-if="transfer.canBeCancel">
                                        <button class="btn btn-md btn-dark" @click="cancel(transfer.id)">Cancel</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </template>

                    </div>
                </div>


                <div class="row justify-content-center" v-if="filters.page">
                    <b-pagination
                        v-model="filters.page"
                        :total-rows="rows"
                        :per-page="perPage"
                        aria-controls="my-table"
                    ></b-pagination>
                </div>
            </div>
        </b-overlay>
    </section>

</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';

    export default {
        name: "TransfersPage",
        components:{
            flatPickr
        },
        data() {
            return {
                transactions: null,
                rows: null,
                perPage: null,
                banks:[],
                bank:null,
                date: '',
                dateConfig: {
                    mode: 'range'
                },
                noResults: false,
                filters: {
                    bank_id: null,
                    account_number: null,
                    from: null,
                    to: null,
                    page: null
                }
            }
        },
        computed: {
            showLoader(){
                return !(this.transactions && this.transactions.length);
            }
        },
        filters: {
            calenderDate(date){
                return moment(date).format('MMMM Do YYYY');
            },
            numbersDate(date){
                return moment(date).format('MM DD YYYY');
            }
        },
        created() {
            this.filters.account_number = this.$route.params.accountNumber || null;
            this.updateTransactions();
            this.fetchBanks().then((response) => {
                this.banks = response.data;
            });
        },
        methods: {
            fetchTransactions(payload){
                return axios({
                    method: 'get',
                    url: '/api/transactions',
                    params: {
                        ...payload,
                        operations: 'transfers'
                    }
                });
            },
            cancelTransfer(transferId){
                return axios({
                    method: 'post',
                    url: `/api/cancelTransfer/${transferId}`
                });
            },
            fetchBanks(){
                return axios({
                    method: 'get',
                    url: '/api/banks'
                })
            },
            updateTransactions(){
                this.noResults = false;
                this.fetchTransactions(this.filters).then((response) => {
                    this.transactions = response.data.data
                    for(let transaction of this.transactions){
                        for(let transfer of transaction.transfers){
                            let now = moment(new Date());
                            let duration = moment.duration(now.diff(moment(transfer.date)));
                            transfer.canBeCancel = duration.asHours() < 36;
                        }
                    }
                    this.noResults = this.transactions.length === 0;
                    this.filters.page = response.data.meta.current_page;
                    this.perPage = response.data.meta.per_page;
                    this.rows = response.data.meta.total;
                })
            },
            clearFilters(){
                for(let filter in this.filters){
                    this.filters[filter] = null;
                }
                this.bank = null;
                this.date = '';
            },
            applyFilters(){
                this.filters.page = 1;
                let date = this.date.split(' to ');
                if(date.length > 1){
                    this.filters.from = date[0];
                    this.filters.to = date[1];
                }
                this.updateTransactions();
            },
            updateBank(bank){
                this.filters.bank_id = bank.id;
            },
            removeBank(){
                this.bank = null;
                this.filters.bank_id = null;
            },
            cancel(transferId){
                let title,variant,message;
                this.cancelTransfer(transferId).then((response) => {
                    if(response.data.canceled){
                        variant = 'success';
                        title = 'Canceled';
                        message = 'transfer canceled successfully';
                        this.filters.page = 1;
                        this.updateTransactions();
                    }else {
                        variant = 'danger';
                        title = 'Failed';
                        message = 'some thing went wrong';
                    }
                    this.makeToast(variant,title,message);
                })
            },
            makeToast(variant,title,message) {
                this.$bvToast.toast(message, {
                    title: title,
                    variant: variant,
                    solid: true
                })
            },
        },
        watch: {
            'filters.page': {
                handler(newVal,oldVal){
                    if(newVal === oldVal){
                        return;
                    }
                    this.updateTransactions();
                }
            }
        }
    }
</script>

<style scoped>

</style>
