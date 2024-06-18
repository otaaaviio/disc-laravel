import {createRouter, createWebHistory} from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import("../pages/home.vue"),
    },
    {
        path: "/login",
        component: () => import("../pages/auth.vue"),
    },
    {
        path: "/dashboard",
        component: () => import("../pages/dashboard.vue"),
    },

];

export default createRouter({
    history: createWebHistory(),
    routes,
});
