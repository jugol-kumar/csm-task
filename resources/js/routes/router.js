import {createRouter, createWebHistory} from 'vue-router';
import {useAuthStore} from "@/stores/auth.js";

const routes = [
    {
        path: '/',
        component: () => import('@/layouts/StoreFrontLayout.vue'),
        children: [
            {
                path: '',
                component: () => import('@/pages/HomePage.vue'),
            },
            {
                path: 'login',
                component: () => import('@/pages/LoginPage.vue'),
                meta: {guest: true},
            },
            {
                path: 'register',
                component: () => import('@/pages/RegistrationPage.vue'),
                meta: {guest: true},
            },
        ],
    },
    {
        path: '/',
        component: () => import('@/layouts/StoreFrontLayout.vue'),
        meta: {ownShopOnly: true},
        children: [
            {
                path: 'shops/:slug',
                component: () => import('@/pages/ShopPage.vue'),
            },
            {
                path: 'shops/:slug/products/:id',
                component: () => import('@/pages/ProductPage.vue'),
            },
        ],
    },
    {
        path: '/admin',
        component: () => import('@/layouts/AdminLayout.vue'),
        meta: {roles: ['admin']},
        children: [
            {
                path: '',
                component: () => import('@/pages/admin/AdminHomePage.vue'),
            },
            {
                path: 'shops',
                component: () => import('@/pages/admin/AdminShopsPage.vue'),
            },
            {
                path: 'products',
                component: () => import('@/pages/admin/AdminProductPage.vue'),
            },
            {
                path: 'users',
                component: () => import('@/pages/admin/AdminUserPage.vue'),
            },
        ],
    },
    {
        path: '/owner',
        component: () => import('@/layouts/OwnerLayout.vue'),
        meta: {roles: ['owner']},
        children: [
            {
                path: '',
                component: () => import('@/pages/owner/OwnerHomePage.vue'),
            },
               {
                    path: 'catalog',
                    component: () => import('@/pages/owner/OwnerCatalogPage.vue'),
               },
                // {
                //     path: 'listings',
                //     component: () => import('@/pages/owner/OwnerListingsPage.vue'),
                // },
                // {
                //     path: 'products/new',
                //     component: () => import('@/pages/owner/OwnerNewProductPage.vue'),
                // },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior: () => ({top: 0}),
});

export function homeFor(role) {
    if (role === 'admin') return '/admin';
    if (role === 'owner') return '/owner';
    return '/';
}

router.beforeEach(async (to) => {
    const auth = useAuthStore();
    await auth.init();

    if (to.meta.roles) {
        if (!auth.isAuthenticated) {
            return {path: '/login', query: {redirect: to.fullPath}};
        }

        if (!to.meta.roles.includes(auth.role)) {
            return homeFor(auth.role);
        }
    }

    if (to.meta.ownShopOnly && auth.isAuthenticated && auth.role === 'owner') {
        const ownSlug = auth.user?.shop?.slug;

        if (!ownSlug) {
            return homeFor(auth.role);
        }

        if (to.params.slug !== ownSlug) {
            return {path: `/shops/${ownSlug}`};
        }
    }

    if (to.meta.guest && auth.isAuthenticated) {
        return homeFor(auth.role);
    }

    return true;
});

export default router;
