<script setup>
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Logo from "@/components/Logo.vue";

const auth = useAuthStore();
const route = useRoute();
const router = useRouter();

const tabs = [
    { to: '/owner', label: 'Overview', exact: true },
    { to: '/owner/catalog', label: 'Catalog' }
];

function isActive(tab) {
    return tab.exact ? route.path === tab.to : route.path.startsWith(tab.to);
}

async function logout() {
    await auth.logout();
    router.push('/login');
}
</script>

<template>
    <div class="flex min-h-screen flex-col bg-slate-50/50 text-slate-800">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <Logo to="/owner" />
                    <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 font-mono text-xs font-semibold text-orange-600 border border-orange-100">
                        Shop desk
                    </span>
                </div>

                <nav class="flex items-center gap-4 sm:gap-6 font-mono text-xs tracking-wider uppercase text-slate-600">
                    <RouterLink to="/" class="transition-colors hover:text-orange-600">Storefront</RouterLink>
                    <span class="hidden text-slate-400 sm:inline">|</span>
                    <span class="hidden text-slate-700 sm:inline">{{ auth.user?.name }}</span>
                    <button
                        type="button"
                        class="rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-rose-300 hover:bg-rose-50 hover:text-rose-600 active:scale-95"
                        @click="logout"
                    >
                        Logout
                    </button>
                </nav>
            </div>

            <div class="mx-auto max-w-6xl overflow-x-auto px-4 sm:px-6">
                <div class="flex w-max gap-2 border-b border-slate-200 pb-3 font-mono text-xs tracking-wider uppercase">
                    <RouterLink
                        v-for="tab in tabs"
                        :key="tab.to"
                        :to="tab.to"
                        class="rounded-lg px-4 py-2 font-medium transition-all duration-200"
                        :class="
                            isActive(tab)
                                ? 'bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 text-white shadow-sm'
                                : 'border border-slate-200 bg-white text-slate-600 hover:border-orange-300 hover:text-orange-600'
                        "
                    >
                        {{ tab.label }}
                    </RouterLink>
                </div>
            </div>
        </header>

        <!-- Main Content View -->
        <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-6">
            <RouterView />
        </main>
    </div>
</template>
