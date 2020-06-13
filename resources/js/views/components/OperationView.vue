<template>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th v-if="operationType">Operation Type</th>
            <th>Date</th>
            <th>Bank</th>
            <th>Payed</th>
            <th>Account Balance</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td v-if="operationType">{{operationType}}</td>
            <td>
                {{operation.date | numbersDate}}
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
                        <td>{{operation.amount}}</td>
                        <td>{{operation.currency}}</td>
                        <td>{{operation.rate}}</td>
                    </tr>
                    </tbody>
                </table>
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
                        <td>{{operation.balance_amount}}</td>
                        <td>{{transaction.account_currency}}</td>
                        <td>{{operation.account_rate}}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

</template>

<script>
    export default {
        name: "OperationView",
        props: {
            operation: {
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
    }
</script>

<style scoped>

</style>
