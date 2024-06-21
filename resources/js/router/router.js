import {createRouter, createWebHistory} from "vue-router";
import store from "../stores/store.js";

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
    {
        path: "/notfound",
        component: () => import("../pages/not-found.vue"),
    },
    {
        path: "/:pathMatch(.*)*",
        redirect: "/notfound",
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.path === '/dashboard' && from.path !== '/login' && !store.getters['auth/isLogged']) {
        next('/login');
    } else if (to.path === '/login' && from.path !== '/dashboard' && store.getters['auth/isLogged']) {
        next('/dashboard');
    } else {
        next();
    }
});

export default router;
