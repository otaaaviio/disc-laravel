import {createRouter, createWebHistory} from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import("./pages/home.vue"),
    },
    {
        path: "/login",
        component: () => import("./pages/auth.vue"),
    },

];

export default createRouter({
    history: createWebHistory(),
    routes,
});
