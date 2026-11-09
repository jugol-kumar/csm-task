<script setup>
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Logo from "@/components/Logo.vue";

const auth = useAuthStore();
const route = useRoute();
const router = useRouter();

async function logout() {
    await auth.logout();
    router.push('/admin/login');
}
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white text-slate-700">
        <!-- Admin Header -->
        <header class="border-b border-slate-200 bg-white shadow-sm">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">

                <!-- Left: Brand & Admin Badge -->
                <div class="flex items-center gap-3">
                    <Logo to="/admin" />
                    <span class="rounded-full bg-amber-50 px-2.5 py-0.5 font-mono text-[11px] font-semibold text-amber-600 border border-amber-200 uppercase tracking-wide">
                        Admin
                    </span>
                </div>

                <!-- Right: Navigation Links -->
                <nav class="flex items-center gap-6 font-mono text-xs font-medium tracking-wider uppercase">
                    <RouterLink
                        v-if="route.path !== '/admin'"
                        to="/admin"
                        class="transition-colors hover:bg-gradient-to-r hover:from-amber-500 hover:to-orange-600 hover:bg-clip-text hover:text-transparent"
                    >
                        Home
                    </RouterLink>

                    <RouterLink
                        to="/"
                        class="transition-colors hover:bg-gradient-to-r hover:from-amber-500 hover:to-orange-600 hover:bg-clip-text hover:text-transparent"
                    >
                        Storefront
                    </RouterLink>

                    <span class="hidden text-slate-400 sm:inline">
                        {{ auth.user?.name }}
                    </span>

                    <button
                        type="button"
                        class="tracking-wider uppercase transition-colors hover:bg-gradient-to-r hover:from-amber-500 hover:to-orange-600 hover:bg-clip-text hover:text-transparent"
                        @click="logout"
                    >
                        Logout
                    </button>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 bg-white text-slate-800">
            <RouterView/>
        </main>
    </div>
</template>
