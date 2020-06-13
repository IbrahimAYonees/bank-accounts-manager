<template>
    <b-overlay :show="inTransaction" rounded="lg">
        <section class="container" v-if="account" :aria-hidden="inTransaction ? 'true' : null">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{account.bank}} {{account.number}}</h5>
                </div>
                <div class="card-body">
                    <span>Account Currency : {{account.currency}}</span>
                    <br>
                    <b-overlay :show="showLoader" rounded="lg">
                    <span :aria-hidden="showLoader ? 'true' : null" v-if="currentBalance">
                        Current Balance : {{currentBalance}}
                    </span>
                    </b-overlay>
                    <div id="type-form" class="form-group col-4">
                        <label for="type">Type</label>
                        <multiselect :options="types"
                                     :searchable="true"
                                     id="type"
                                     :close-on-select="true"
                                     track-by="value"
                                     :show-labels="true"
                                     :hide-selected="false"
                                     placeholder="choose a transaction type"
                                     label="text"
                                     :internal-search="true"
                                     v-model="type"
                                     :allow-empty="false"
                                     selectLabel=""
                                     deselectLabel=""
                                     selectedLabel=""
                                     :disabled="operationsVisible || transferVisible"
                        >
                        </multiselect>
                    </div>
                </div>
                <div class="card-footer">
                    <b-button @click="show"
                              variant="dark"
                              class="offset-9"
                              :disabled="operationsVisible || transferVisible"
                    >Start New Transaction</b-button>
                </div>
            </div>
            <div>
                <b-collapse :visible="operationsVisible" class="mt-2">
                    <b-card>
                        <button class="btn btn-md btn-dark" @click="addOperation('deposit')">Add Deposit</button>
                        <button class="btn btn-md btn-dark" @click="addOperation('withdraw')">Add Withdraw</button>
                        <button class="btn btn-md btn-light offset-9" v-if="!operations.length" @click="cancelTransaction">Cancel</button>

                        <div class="card" v-for="operation in operations">
                            <div class="card-header">
                                <h5 class="card-title">{{operation.type}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text"
                                           class="form-control"
                                           :class="{'is-invalid': operation.errors && operation.errors.amount}"
                                           name="amount"
                                           v-model="operation.amount"
                                    >
                                    <div class="text-danger invalid-feedback" style="display: block;"
                                         v-if="operation.errors && operation.errors.amount"
                                    >
                                        {{operation.errors.amount}}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Currency</label>
                                    <multiselect :options="currencies"
                                                 :searchable="true"
                                                 :close-on-select="true"
                                                 track-by="id"
                                                 :show-labels="true"
                                                 :hide-selected="false"
                                                 placeholder="choose a currency"
                                                 label="name"
                                                 :internal-search="true"
                                                 v-model="operation.currency"
                                                 :class="{'is-invalid': operation.errors && operation.errors.currency}"
                                    >
                                    </multiselect>
                                    <div class="text-danger invalid-feedback" style="display: block;"
                                         v-if="operation.errors && operation.errors.currency"
                                    >
                                        {{operation.errors.currency}}
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button class="btn btn-md btn-dark offset-11" @click="removeOperation(operation)">remove</button>
                            </div>
                        </div>
                        <div class="card-footer" v-if="operations.length">
                            <button class="btn btn-md btn-light offset-9" @click="cancelTransaction">Cancel</button>
                            <button class="btn btn-md btn-dark" @click="finishTransaction">Finish Transaction</button>
                        </div>
                    </b-card>
                </b-collapse>
                <b-collapse :visible="transferVisible" class="mt-2">
                    <b-card>
                        <div class="card" v-if="transfer">
                            <div class="card-header">
                                <h5 class="card-title">Transfer</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text"
                                           class="form-control"
                                           :class="{'is-invalid': transfer.errors && transfer.errors.amount}"
                                           name="amount"
                                           v-model="transfer.amount"
                                    >
                                    <div class="text-danger invalid-feedback" style="display: block;"
                                         v-if="transfer.errors && transfer.errors.amount"
                                    >
                                        {{transfer.errors.amount}}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Currency</label>
                                    <multiselect :options="currencies"
                                                 :searchable="true"
                                                 :close-on-select="true"
                                                 track-by="id"
                                                 :show-labels="true"
                                                 :hide-selected="false"
                                                 placeholder="choose a currency"
                                                 label="name"
                                                 :internal-search="true"
                                                 v-model="transfer.currency"
                                                 :class="{'is-invalid': transfer.errors && transfer.errors.currency}"
                                    >
                                    </multiselect>
                                    <div class="text-danger invalid-feedback" style="display: block;"
                                         v-if="transfer.errors && transfer.errors.currency"
                                    >
                                        {{transfer.errors.currency}}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>To Bank</label>
                                    <input type="text"
                                           class="form-control"
                                           :class="{'is-invalid': transfer.errors && transfer.errors.to_bank}"
                                           name="to_bank"
                                           v-model="transfer.to_bank"
                                    >
                                    <div class="text-danger invalid-feedback" style="display: block;"
                                         v-if="transfer.errors && transfer.errors.to_bank"
                                    >
                                        {{transfer.errors.to_bank}}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>To Bank Account Number</label>
                                    <input type="text"
                                           class="form-control"
                                           :class="{'is-invalid': transfer.errors && transfer.errors.to_bank_account_number}"
                                           name="to_bank_account_number"
                                           v-model="transfer.to_bank_account_number"
                                    >
                                    <div class="text-danger invalid-feedback" style="display: block;"
                                         v-if="transfer.errors && transfer.errors.to_bank_account_number"
                                    >
                                        {{transfer.errors.to_bank_account_number}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-md btn-light offset-9" @click="cancelTransaction">Cancel</button>
                            <button class="btn btn-md btn-dark" @click="finishTransaction">Finish Transaction</button>
                        </div>
                    </b-card>
                </b-collapse>

            </div>
            <v-dialog :clickToClose="false"></v-dialog>
        </section>

    </b-overlay>
</template>

<script>
    import {getBalance,fetchCurrencies,startTransaction,doDeposit,doWithdraw,doTransfer} from "@/api";
    import {copyObject,isFloat} from "@/helpers/helperFunctions";

    export default {
        name: "TransactionPage",
        data() {
            return {
                inTransaction: false,
                account: null,
                currentBalance: null,
                currencies: [],
                creating: false,
                operationsVisible: false,
                transferVisible: false,
                types: [
                    {text: 'Deposits / Withdraws' , value: 'operation'},
                    {text: 'Transfer' ,value: 'transfer'}
                ],
                type: {text: 'Deposits / Withdraws' , value: 'operation'},
                operations: [],
                transfer: null,
                operationForm: {
                    type: null,
                    amount: null,
                    currency: null,
                    currency_id: null,
                    errors: {}
                },
                transferForm:{
                    amount: null,
                    currency: null,
                    currency_id: null,
                    to_bank: null,
                    to_bank_account_number: null,
                    errors: {}
                }
            }
        },
        computed: {
            showLoader(){
                return this.currentBalance === null;
            }
        },
        created() {
            if(!this.$route.params || !this.$route.params.account){
                this.$router.push({
                    name: 'Accounts'
                });
                return;
            }
            this.account = this.$route.params.account;
            getBalance(this.account.id).then((response) => {
                this.currentBalance = response.data.balance;
            });
            fetchCurrencies().then((response) => {
                this.currencies = response.data;
            })
        },
        methods: {
            addOperation(type){
                this.creating = true;
                let operationForm = copyObject(this.operationForm);
                operationForm.type = type;
                this.operations.push(operationForm);
            },
            removeOperation(operation){
                let index = this.operations.indexOf(operation);
                if(index > -1){
                    this.operations.splice(index,1);
                }
            },
            show(){
                switch (this.type.value) {
                    case 'operation':
                        this.operationsVisible = true;
                        break;
                    case 'transfer':
                        this.transferVisible = true;
                        this.transfer = copyObject(this.transferForm);
                        break;
                }
            },
            cancelTransaction(){
                this.operations = [];
                this.transfer = null;
                this.operationsVisible = false;
                this.transferVisible = false;
            },
            async finishTransaction(){
                this.inTransaction = true;
                let id;
                switch (this.type.value) {
                    case 'operation':
                        if(!this.validateOperations()){
                            this.inTransaction = false;
                            return;
                        }
                        await startTransaction(this.account.id).then((response) => {
                            id = response.data.transaction_id;
                        })
                        for(let operation of this.operations){
                            let payload = {
                                transaction_id: id,
                                currency_id: operation.currency_id,
                                amount: operation.amount
                            };
                            switch (operation.type) {
                                case 'deposit':
                                    await doDeposit(this.account.id,payload);
                                    break;
                                case 'withdraw':
                                    await doWithdraw(this.account.id,payload).catch((err) =>{
                                        this.$modal.show('dialog',{
                                            text: 'Transaction Failed Insufficient Balance',
                                            buttons: [
                                                {
                                                    title: 'new transaction',
                                                    handler: () => {
                                                        this.cancelTransaction();
                                                        this.$modal.hide('dialog');
                                                    }
                                                },
                                                {
                                                    title: 'go to accounts',
                                                    handler: () => {
                                                        this.cancelTransaction();
                                                        this.$router.push({
                                                            name: 'Accounts'
                                                        });
                                                        this.$modal.hide('dialog');
                                                    }
                                                },
                                            ]
                                        });
                                    });
                                    break;
                            }
                        }
                        break;
                    case 'transfer':
                        if(!this.validateTransfer()){
                            this.inTransaction = false;
                            return;
                        }
                        await startTransaction(this.account.id).then((response) => {
                            id = response.data.transaction_id;
                        })
                        let payload = {
                            transaction_id: id,
                            currency_id: this.transfer.currency_id,
                            amount: this.transfer.amount,
                            to_bank: this.transfer.to_bank,
                            to_bank_account_number: this.transfer.to_bank_account_number
                        };
                        await doTransfer(this.account.id,payload).catch((err) => {
                            this.$modal.show('dialog',{
                                text: 'Transaction Failed Insufficient Balance',
                                buttons: [
                                    {
                                        title: 'new transaction',
                                        handler: () => {
                                            this.cancelTransaction();
                                            this.$modal.hide('dialog');
                                        }
                                    },
                                    {
                                        title: 'go to accounts',
                                        handler: () => {
                                            this.cancelTransaction();
                                            this.$router.push({
                                                name: 'Accounts'
                                            });
                                            this.$modal.hide('dialog');
                                        }
                                    },
                                ]
                            });

                        })
                        break;
                }
                this.$modal.show('dialog',{
                    text: 'Transaction Completed Successfully',
                    buttons: [
                        {
                            title: 'new transaction',
                            handler: () => {
                                this.cancelTransaction();
                                getBalance(this.account.id).then((response) => {
                                    this.currentBalance = response.data.balance
                                });
                                this.$modal.hide('dialog');
                            }
                        },
                        {
                            title: 'go to accounts',
                            handler: () => {
                                this.cancelTransaction();
                                this.$router.push({
                                    name: 'Accounts'
                                });
                                this.$modal.hide('dialog');
                            }
                        },
                    ]
                });
                this.inTransaction = false;
            },
            validateOperations(){
                let isValid = true;
                for(let operation of this.operations){
                    operation.errors = {};
                    if(!operation.currency){
                        isValid = false;
                        if(!operation.errors){
                            operation.errors = {};
                        }
                        operation.errors.currency = 'you must choose a currency';
                    }else {
                        operation.currency_id = operation.currency.id;
                    }
                    if(!operation.amount){
                        isValid = false;
                        if(!operation.errors){
                            operation.errors = {};
                        }
                        operation.errors.amount = 'you must enter amount';
                    }else if(!isFloat(operation.amount)){
                        isValid = false;
                        if(!operation.errors){
                            operation.errors = {};
                        }
                        operation.errors.amount = 'you must enter a valid amount';
                    }
                }
                this.operations = copyObject(this.operations);
                return isValid;
            },
            validateTransfer(){
                let isValid = true;
                this.transfer.errors = {};
                if(!this.transfer.to_bank){
                    isValid = false;
                    if(!this.transfer.errors){
                        this.transfer.errors = [];
                    }
                    this.transfer.errors.to_bank = 'you must type a bank name';
                }
                if(!this.transfer.to_bank_account_number){
                    isValid = false;
                    if(!this.transfer.errors){
                        this.transfer.errors = [];
                    }
                    this.transfer.errors.to_bank_account_number = 'you must type a bank account number';
                }
                if(!this.transfer.currency){
                    isValid = false;
                    if(!this.transfer.errors){
                        this.transfer.errors = [];
                    }
                    this.transfer.errors.currency = 'you must choose a currency';
                }else {
                    this.transfer.currency_id = this.transfer.currency.id;
                }
                if(!this.transfer.amount){
                    isValid = false;
                    if(!this.transfer.errors){
                        this.transfer.errors = {};
                    }
                    this.transfer.errors.amount = 'you must enter amount';
                }else if(!isFloat(this.transfer.amount)){
                    isValid = false;
                    if(!this.transfer.errors){
                        this.transfer.errors = {};
                    }
                    this.transfer.errors.amount = 'you must enter a valid amount';
                }
                this.transfer = copyObject(this.transfer);
                return isValid;
            }
        }

    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>

</style>
