import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Vue from 'vue';
import {attempt, authenticated, user} from "@/auth";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
import App from '@/views/layout/Layout';

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
        }
    },
});

import router from '@/router';


attempt().then(() => {
    const app = new Vue({
        el: '#app',
        router,
        components:{
            App
        }
    });
});

