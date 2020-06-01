import LoginPage from "@/views/LoginPage";
import AccountsPage from '@/views/accounts/AccountsPage';
import NotFoundPage from "@/views/NotFoundPage";
import {authenticated} from "@/auth";


const redirectIfAuthenticated = (next) => {
    authenticated() ? next('/accounts') : next();
};

const redirectIfNotAuthenticated = (next) => {
    !authenticated() ? next({name: 'login'}) : next();
};


export default [
    {
        path: '/accounts',
        name: 'Accounts',
        component: AccountsPage,
        meta: {
            title: 'Accounts'
        },
        beforeEnter: (to, from, next) => redirectIfNotAuthenticated(next),
    },
    {
        path: '/login',
        name: 'login',
        component: LoginPage,
        meta: {
            title: 'Login'
        },
        beforeEnter: (to, from, next) => redirectIfAuthenticated(next),
    },
    {
        path: '/',
        name: 'Not_Found',
        component: NotFoundPage,
        meta: {
            title: 'Not Found'
        }
    },
    {
        path: '*', // default page Or 404 page
        beforeEnter: (to, from, next) => {
            return !authenticated ? next('/login') : next('/')
        }
    },
];
