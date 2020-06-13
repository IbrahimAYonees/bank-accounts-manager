<template>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th v-if="operationType">Operation Type</th>
            <th>Date</th>
            <th>From</th>
            <th>From</th>
            <th>Account Balance</th>
            <th>Cancellation Status</th>
            <th v-if="!transfer.canceled && transfer.canBeCancel">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td v-if="operationType">{{operationType}}</td>
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
            <td>
                <span v-if="transfer.canceled">Canceled</span>
                <span v-else-if="!transfer.canceled && transfer.canBeCancel">Can Be Canceled</span>
                <span v-else>Permanent</span>
            </td>
            <td v-if="!transfer.canceled && transfer.canBeCancel">
                <button class="btn btn-md btn-dark" @click="cancel(transfer.id)">Cancel</button>
            </td>
        </tr>
        </tbody>
    </table>

</template>

<script>
    export default {
        name: "TransferView",
        props: {
            transfer: {
                type: Object,
                required: true
            },
            transaction: {
                type: Object,
                required: true
            },
            operationType: {
                type: String,
                default: null
            }
        },
        methods: {
            cancel(transferId){
                this.$emit('canceling',transferId);
            }
        }
    }
</script>

<style scoped>

</style>
