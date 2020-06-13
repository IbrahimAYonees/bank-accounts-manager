<template>
    <section class="container">
        <transactions-filters @apply="applyFilters"
                              @clear="clearFilters"
        ></transactions-filters>

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
                        <operation-view v-for="deposit in transaction.deposits"
                                  :key="deposit.id"
                                  :operation="deposit"
                                  :transaction="transaction"
                                  operationType="Deposit"
                        ></operation-view>

                        <operation-view v-for="withdraw in transaction.withdraws"
                                  :key="withdraw.id"
                                  :operation="withdraw"
                                  :transaction="transaction"
                                  operationType="Withdraw"
                        ></operation-view>

                        <transfer-view v-for="transfer in transaction.transfers"
                                       :key="transfer.id"
                                       :transfer="transfer"
                                       :transaction="transaction"
                                       operationType="Transfer"
                        ></transfer-view>
                    </div>
                </div>
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
    </section>

</template>

<script>
    import {fetchBanks,fetchTransactions} from "@/api";
    import TransferView from "@/views/components/TransferView";
    import OperationView from "@/views/components/OperationView";
    import TransactionsFilters from "@/views/components/TransactionsFilters";

    export default {
        name: "TransactionsPage",
        components:{
            TransactionsFilters,
            TransferView,
            OperationView
        },
        data() {
            return {
                transactions: null,
                rows: null,
                currentPage: null,
                perPage: null,
                date: '',
                noResults: false,
                filters: null
            }
        },
        computed: {
            showLoader(){
                return !(this.transactions && this.transactions.length);
            }
        },
        created() {
            this.updateTransactions();
        },
        methods: {
            updateTransactions(){
                this.noResults = false;
                let payload = {
                    ...this.filters,
                    page: this.currentPage
                };
                fetchTransactions(payload).then((response) => {
                    this.transactions = response.data.data
                    this.noResults = this.transactions.length === 0;
                    this.currentPage = response.data.meta.current_page;
                    this.perPage = response.data.meta.per_page;
                    this.rows = response.data.meta.total;
                })
            },
            clearFilters(){
                this.filters = null;
                this.currentPage = 1;
                this.updateTransactions();
            },
            applyFilters(filters){
                this.filters = filters;
                this.currentPage = 1;
                this.updateTransactions();
            }
        },
        watch: {
            currentPage(newVal,oldVal){
                if(newVal === oldVal){
                    return;
                }
                this.updateTransactions();
            }
        }
    }
</script>

<style scoped>

</style>
