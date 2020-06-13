//front end routing middleware

import {authenticated} from "@/auth";

export const redirectIfAuthenticated = (next) => {
    authenticated() ? next('/accounts') : next();
};

export const redirectIfNotAuthenticated = (next) => {
    !authenticated() ? next({name: 'login'}) : next();
};
