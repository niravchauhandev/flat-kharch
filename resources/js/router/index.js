import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '@/views/auth/LoginView.vue';
import DashboardView from '@/views/dashboard/DashboardView.vue';
import MembersView from '@/views/members/MembersView.vue';

const routes = [
    {
        path: '/spa',
        redirect: '/spa/dashboard',
    },
    {
        path: '/spa/login',
        name: 'login',
        component: LoginView,
        meta: { guestOnly: true },
    },
    {
        path: '/spa/dashboard',
        name: 'dashboard',
        component: DashboardView,
        meta: { requiresAuth: true },
    },
    {
        path: '/spa/members',
        name: 'members',
        component: MembersView,
        meta: { requiresAuth: true },
    },
    {
        path: '/spa/expenses',
        name: 'expenses',
        component: DashboardView, // Quick link back to dashboard / expenses
        meta: { requiresAuth: true },
    },
    {
        path: '/spa/pool',
        name: 'pool',
        component: DashboardView,
        meta: { requiresAuth: true },
    },
    {
        path: '/spa/settlements',
        name: 'settlements',
        component: DashboardView,
        meta: { requiresAuth: true },
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/spa/dashboard',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('flat_kharch_token');

    if (to.meta.requiresAuth && !token) {
        return next({ name: 'login' });
    }

    if (to.meta.guestOnly && token) {
        return next({ name: 'dashboard' });
    }

    next();
});

export default router;
