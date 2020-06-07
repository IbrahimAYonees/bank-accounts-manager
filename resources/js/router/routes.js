import LoginPage from "@/views/LoginPage";
import AccountsPage from '@/views/accounts/AccountsPage';
import NotFoundPage from "@/views/NotFoundPage";
import HomePage from '@/views/HomePage';
import TransactionPage from "@/views/transaction/TransactionPage";
import TransactionsPage from "@/views/transactions/TransactionsPage";
import DepositsPage from "@/views/deposits/DepositsPage";
import WithdrawsPage from "@/views/withdraws/WithdrawsPage";
import TransfersPage from "@/views/transfers/TransfersPage";
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
        path: '/transactions',
        name: 'transactions',
        component: TransactionsPage,
        meta: {
            title: 'Transactions'
        },
        beforeEnter: (to, from, next) => redirectIfNotAuthenticated(next),
    },
    {
        path: '/transaction',
        name: 'transaction',
        component: TransactionPage,
        meta: {
            title: 'Transaction'
        },
        beforeEnter: (to, from, next) => redirectIfNotAuthenticated(next),
    },
    {
        path: '/deposits',
        name: 'deposits',
        component: DepositsPage,
        meta: {
            title: 'Deposits'
        },
        beforeEnter: (to, from, next) => redirectIfNotAuthenticated(next),
    },
    {
        path: '/withdraws',
        name: 'withdraws',
        component: WithdrawsPage,
        meta: {
            title: 'Withdraws'
        },
        beforeEnter: (to, from, next) => redirectIfNotAuthenticated(next),
    },
    {
        path: '/transfers',
        name: 'transfers',
        component: TransfersPage,
        meta: {
            title: 'Transfers'
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
        path: '/not_found',
        name: 'Not_Found',
        component: NotFoundPage,
        meta: {
            title: '404'
        },
    },
    {
        path: '/',
        name: 'home_page',
        component: HomePage,
        meta: {
            title: 'Home'
        }
    },
    {
        path: '*', // default page Or 404 page
        beforeEnter: (to, from, next) => next('/not_found'),
    }
];
