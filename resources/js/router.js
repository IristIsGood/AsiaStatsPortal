import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from './stores/auth';
import Dashboard    from './views/Dashboard.vue';
import Indicators   from './views/Indicators.vue';
import DataExplorer from './views/DataExplorer.vue';
import Login        from './views/Login.vue';
import Admin        from './views/Admin.vue';

const routes = [
    { path: '/',            component: Dashboard },
    { path: '/indicators',  component: Indicators },
    { path: '/explorer',    component: DataExplorer },
    { path: '/login',       component: Login },
    {
        path: '/admin',
        component: Admin,
        meta: { requiresAuth: true }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// 路由守卫：需要登录的页面自动跳转到 Login
router.beforeEach((to) => {
    const auth = useAuthStore();
    if (to.meta.requiresAuth && !auth.isLoggedIn) {
        return { path: '/login' };
    }
});

export default router;