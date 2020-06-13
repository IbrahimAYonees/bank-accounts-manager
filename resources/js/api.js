//this file holds the api calls
//any update or change of api urls will be here and don't affect the usage of this calls in the components

import axios from 'axios';

export const fetchAccounts = (page = 1) => {
    return axios({
        method: 'get',
        url: '/api/accounts',
        params: {
            page
        }
    });
};

export const createAccount = (payload) => {
    return axios({
        method: 'post',
        url: '/api/accounts',
        data: {
            ...payload
        }
    });
};

export const editAccount = (payload,accountId) => {
    return axios({
        method: 'put',
        url: `/api/accounts/${accountId}`,
        data: {
            ...payload
        }
    });
};

export const activateAccount = (accountId) => {
    return axios({
        method: 'patch',
        url: `/api/accounts/${accountId}/activate`
    });
};

export const deactivateAccount = (accountId) => {
    return axios({
        method: 'patch',
        url: `/api/accounts/${accountId}/deactivate`
    });
};

export const fetchBanks = () => {
    return axios({
        method: 'get',
        url: '/api/banks'
    });
};

export const fetchCurrencies = () => {
    return axios({
        method: 'get',
        url: '/api/currencies'
    });
};

export const fetchTransactions = (payload , operation) => {
    if(operation){
        payload.operations = operation;
    }

    return axios({
        method: 'get',
        url: '/api/transactions',
        params: {
            ...payload
        }
    });
};

export const getBalance = (accountId) => {
    return axios({
        method: 'get',
        url: `/api/accounts/${accountId}/getBalance`
    });
};

export const startTransaction = (accountId) => {
    return axios({
        method: 'post',
        url: `/api/transactions/${accountId}`
    });
};

export const doDeposit = (accountId,payload) => {
    return axios({
        method: 'post',
        url: `/api/doDeposit/${accountId}`,
        data: {
            ...payload
        }
    });
};

export const doWithdraw = (accountId,payload) => {
    return axios({
        method: 'post',
        url: `/api/doWithdraw/${accountId}`,
        data: {
            ...payload
        }
    })
};

export const doTransfer = (accountId,payload) => {
    return axios({
        method: 'post',
        url: `/api/doTransfer/${accountId}`,
        data: {
            ...payload
        }
    })
};

export const cancelTransfer = (transferId) => {
    return axios({
        method: 'post',
        url: `/api/cancelTransfer/${transferId}`
    });
};

export const getAnalysis = () => {
    return axios({
        method: 'get',
        url: '/api/statistics/banks'
    })
};
