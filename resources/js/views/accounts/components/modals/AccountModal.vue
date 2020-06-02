<template>
    <modal name="account-modal"
           @before-close="closing"
           @before-open="opening"
           height="auto"
           :resizable="true"
           :adaptive="true"
    >
        <template v-if="true">
            <div class="card">
                <div class="card-header bg-dark text-white text-center">
                    <h3 class="card-title mt-1">{{action}} Account</h3>
                </div>
                <hr>
                <div class="card-body">
                    <div id="bank-form" :class="{
                            'form-group':true,
                            'has-error':$v.bank_id.$anyError
                        }"
                    >
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
                        <div class="text-danger invalid-feedback" style="display: block;"
                             v-if="!$v.bank_id.required"
                        >
                            you must choose a bank
                        </div>
                    </div>
                    <div id="currency-form" :class="{
                            'form-group':true,
                            'has-error':$v.currency_id.$anyError
                        }"
                    >
                        <label for="currency">Currency</label>
                        <multiselect :options="currencies"
                                     :searchable="true"
                                     id="currency"
                                     :close-on-select="true"
                                     track-by="id"
                                     :show-labels="true"
                                     :hide-selected="false"
                                     placeholder="choose a currency"
                                     label="name"
                                     :internal-search="true"
                                     v-model="currency"
                                     @select="updateCurrency"
                                     @remove="removeCurrency"
                        >
                        </multiselect>
                        <div class="text-danger invalid-feedback" style="display: block;"
                             v-if="!$v.currency_id.required"
                        >
                            you must choose a currency
                        </div>
                    </div>
                    <div id="type-form" :class="{
                            'form-group':true,
                            'has-error':$v.type.$anyError
                        }"
                    >
                        <label for="type">Type</label>
                        <multiselect :options="types"
                                     :searchable="true"
                                     id="type"
                                     :close-on-select="true"
                                     :show-labels="true"
                                     :hide-selected="false"
                                     placeholder="choose a type"
                                     :internal-search="true"
                                     v-model="type"
                        >
                        </multiselect>
                        <div class="text-danger invalid-feedback" style="display: block;"
                             v-if="!$v.type.required"
                        >
                            you must choose a type
                        </div>
                    </div>
                    <div :class="{
                            'form-group':true,
                            'has-error':$v.branch.$anyError
                        }"
                    >
                        <label for="branch">Branch</label>
                        <input type="text"
                               class="form-control"
                               name="branch"
                               id="branch"
                               v-model="branch"
                        >
                        <div class="text-danger invalid-feedback" style="display: block;"
                             v-if="!$v.branch.required"
                        >
                            you must type a branch
                        </div>
                    </div>
                    <div class="row justify-content-end m-0">
                        <button class="btn btn-light btn-md"
                                @click.prevent="cancel"
                        >
                            Cancel
                        </button>
                        <button class="btn btn-dark btn-md new_user"
                                @click.prevent="submitForm"
                                :disabled="inAction"
                        >
                            {{action | submitAction}}
                        </button>
                    </div>

                </div>
            </div>
        </template>
    </modal>

</template>

<script>
    import {required} from 'vuelidate/lib/validators';
    import axios from 'axios';

    export default {
        name: "AccountModal",
        data() {
            return {
                bank_id: null,
                currency_id: null,
                type: null,
                branch: null,
                bank: null,
                banks: [],
                currency: null,
                currencies: [],
                types: [
                    'Current', 'Saving', 'Credit', 'Joint'
                ],
                old: null,
                action: 'New',
                inAction: false
            }
        },
        filters: {
            submitAction(action){
                return action === 'New' ? 'Create' : 'Edit'
            }
        },
        created() {
            this.fetchBanks().then((response) => {
                this.banks = response.data;
            });
            this.fetchCurrencies().then((response) => {
                this.currencies = response.data;
            });
        },
        validations: {
            bank_id: {
                required
            },
            currency_id: {
                required
            },
            type: {
                required
            },
            branch: {
                required
            }
        },
        methods: {
            fetchBanks() {
                return axios({
                    method: 'get',
                    url: '/api/banks'
                });
            },
            fetchCurrencies() {
                return axios({
                    method: 'get',
                    url: '/api/currencies'
                });
            },
            createAccount(payload) {
                return axios({
                    method: 'post',
                    url: '/api/accounts',
                    data: {
                        ...payload
                    }
                });
            },
            editAccount(payload, id) {
                return axios({
                    method: 'put',
                    url: `/api/accounts/${id}`,
                    data: {
                        ...payload
                    }
                })
            },
            async submitForm() {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    return;
                }
                this.inAction = true;
                let payload = {
                    bank_id: this.bank_id,
                    currency_id: this.currency_id,
                    type: this.type,
                    branch: this.branch
                };
                let failed = false;
                switch (this.action) {
                    case 'New':
                        await this.createAccount(payload).catch((err) =>{
                            failed = true;
                        });
                        break;
                    case 'Edit':
                        await this.editAccount(payload, this.old.id).catch((err) =>{
                            failed = true;
                        });
                        break;
                }
                this.inAction = false;
                if(failed){
                    return;
                }
                this.$emit('changed',this.action);
                this.$modal.hide('account-modal');
            },
            updateBank(bank) {
                this.bank_id = bank.id;
            },
            removeBank() {
                this.bank_id = null;
            },
            updateCurrency(currency) {
                this.currency_id = currency.id;
            },
            removeCurrency() {
                this.currency_id = null;
            },
            opening(e) {
                if(!e.params || !e.params.account){
                    return;
                }
                this.old = e.params.account;
                this.action = 'Edit';
                this.bank_id = this.old.bank_id;
                this.bank = this.getById(this.banks,this.bank_id);
                this.currency_id = this.old.currency_id;
                this.currency = this.getById(this.currencies,this.currency_id);
                this.type = this.old.type;
                this.branch = this.old.branch;
             },
            cancel(){
                this.$modal.hide('account-modal');
            },
            closing() {
                this.old = null;
                this.action = 'New';
                this.bank_id = null;
                this.bank = null;
                this.currency_id = null;
                this.currency = null;
                this.type = null;
                this.branch = null;
                this.inAction = false;
            },
            getById(collection , id){
                for(let item of collection){
                    if(item.id === id){
                        return item;
                    }
                }
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>

</style>
