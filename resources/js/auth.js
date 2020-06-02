import axios from 'axios';
import {bus} from '@/app';

let apiToken = null;
let authUser = null;

export const authenticated = () => {
    return apiToken && authUser;
};

export const user = () => {
    return  authUser;
};

export const attempt = () => {
    apiToken = localStorage.getItem('api_token');
    setAxiosAuth();
    return axios.get('/api/me').then(response => {
        authUser = response.data;
    }).catch((error) => {
        if(error.response.status == 401){
            localStorage.removeItem('api_token');
            apiToken = null;
            authUser = null;
        }
    }).finally(emitUpdatedAuth);
};

export const login = (email, password) => {
    return axios.post('/api/login', {email, password})
        .then(response => {
            authUser = response.data.user;
            apiToken = response.data.access_token;
            localStorage.setItem('api_token', apiToken);
            setAxiosAuth();
            emitUpdatedAuth();
        });
};

export const logout = () => {
    localStorage.removeItem('api_token');
    apiToken = null;
    authUser = null;
    setAxiosAuth(true);
    emitUpdatedAuth();
};

const setAxiosAuth = (clear = false) => {
    axios.defaults.headers.common['Authorization'] = clear ? null : `Bearer ${apiToken}`;
};

const emitUpdatedAuth = () => bus.$emit('updateAuth', {user: user(), authenticated: authenticated()});

