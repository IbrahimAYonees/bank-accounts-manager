<template>
    <modal name="account-options-modal"
           @before-close="closing"
           @before-open="opening"
           height="auto"
           :resizable="true"
           :adaptive="true"
    >
        <div class="card">
            <div class="card-body row">
                <button class="btn btn-dark btn-sm col-4 m-2" @click.prevent="goTo('transactions')">Show Transactions</button>
                <button class="btn btn-dark btn-sm col-4 m-2" @click.prevent="goTo('deposits')">Show Deposits</button>
                <button class="btn btn-dark btn-sm col-4 m-2" @click.prevent="goTo('withdraws')">Show Withdraws</button>
                <button class="btn btn-dark btn-sm col-4 m-2" @click.prevent="goTo('transfers')">Show Transfers</button>
                <button class="btn btn-dark btn-sm col-4 m-2"
                        @click.prevent="goTo('transaction')"
                        v-if="account && account.active"
                >New Transaction</button>
            </div>
        </div>
    </modal>
</template>

<script>
    export default {
        name: "AccountOptionsModal",
        data() {
            return {
                account: null
            }
        },
        methods: {
            opening(e){
                this.account = e.params.account || null;
            },
            closing(){
                this.account = null;
            },
            goTo(pageName){
                if(!this.account){
                    return;
                }
                this.$router.push({
                    name: pageName,
                    params:{
                        accountNumber: this.account.number,
                        account: this.account
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
