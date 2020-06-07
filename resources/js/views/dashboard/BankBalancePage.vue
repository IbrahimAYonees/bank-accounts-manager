<template>
    <section class="container">
        <div class="card col-5">
            <h3 class="card-header text-center">Balans In Banks</h3>
            <div class="card-body">
                <canvas  id="pie-chart"></canvas>
            </div>
        </div>
    </section>
</template>

<script>
    import Chart from 'chart.js';
    import axios from 'axios';

    export default {
        name: "BankBalancePage",
        data(){
            return {
                banks: []
            }
        },
        created() {
            this.fetchBankAnalysis().then((response) => {
                this.banks = response.data.banksBalances;
                setTimeout(()=>{
                    this.initChart();
                },500);
            })
        },
        methods: {
            fetchBankAnalysis(){
                return axios({
                    method: 'get',
                    url: '/api/statistics/banks'
                })
            },
            initChart(){
                let labels = [];
                let data = [];
                for(let bank in this.banks){
                    labels.push(bank);
                    data.push(this.banks[bank])
                }
                let ctx = document.getElementById('pie-chart');
                return new Chart(ctx,{
                    type: 'doughnut',
                    data:{
                        labels: labels,
                        datasets:[{
                            label: 'Balance In Banks',
                            data: data
                        }],
                    },
                });

            }
        }
    }
</script>

<style scoped>

</style>
