<template>
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
</template>

<script>
    import {fetchBanks} from "@/api";
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';

    export default {
        name: "TransactionsFilters",
        components: {
            flatPickr
        },
        data() {
            return {
                filters: {
                    bank_id: null,
                    account_number: null,
                    from: null,
                    to: null,
                },
                bank: null,
                banks: [],
                date: '',
                dateConfig: {
                    mode: 'range'
                },
            }
        },
        created() {
            this.filters.account_number = this.$route.params.accountNumber || null;
            if(this.filters.account_number){
                this.applyFilters();
            }
            fetchBanks().then((response) => {
                this.banks = response.data;
            });
        },
        methods: {
            updateBank(bank){
                this.filters.bank_id = bank.id;
            },
            removeBank(){
                this.bank = null;
                this.filters.bank_id = null;
            },
            clearFilters(){
                for(let filter in this.filters){
                    this.filters[filter] = null;
                }
                this.bank = null;
                this.date = '';
                this.$emit('clear');
            },
            applyFilters(){
                let date = this.date.split(' to ');
                if(date.length > 1){
                    this.filters.from = date[0];
                    this.filters.to = date[1];
                }
                this.$emit('apply',{...this.filters});
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>

</style>
