//here is the initial of the SPA app
//starting the vue app and injecting all globals to it
//this the entry point to the front end app

import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Vue from 'vue';
import {attempt, authenticated, user} from "@/auth";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
import App from '@/views/layout/Layout';
import VModal from 'vue-js-modal';
import Multiselect from 'vue-multiselect';
import Vuelidate from 'vuelidate';
import router from '@/router';
import moment from "moment";

Vue.use(Vuelidate);
Vue.component('multiselect', Multiselect);
Vue.use(VModal, { dialog: true });

export const bus = new Vue();

Vue.mixin({
    mounted() {
        this.updateAuth({authenticated: authenticated(), user: user()});
        bus.$on('updateAuth', this.updateAuth);
    },
    data() {
        return {
            authenticated: false,
            authUser: {}
        }
    },
    methods: {
        updateAuth({authenticated, user}) {
            this.authenticated = authenticated;
            this.authUser = user;
        },
        makeToast(variant,title,message) {
            this.$bvToast.toast(message, {
                title: title,
                variant: variant,
                solid: true
            })
        }
    },
    filters: {
        calenderDate(date){
            return moment(date).format('MMMM Do YYYY');
        },
        numbersDate(date){
            return moment(date).format('MM DD YYYY');
        }
    }
});

attempt().then(() => {
    const app = new Vue({
        el: '#app',
        router,
        components:{
            App
        }
    });
});

