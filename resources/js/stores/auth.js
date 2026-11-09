import {computed, ref} from 'vue';
import {defineStore} from 'pinia';
import {TOKEN_KEY} from "@/utils/api.js";
import {authService} from "@/services/auth.service.js";

export const useAuthStore = defineStore('auth', () => {
    // state
    const token = ref(localStorage.getItem(TOKEN_KEY));
    const user = ref(null);
    const ready = ref(false);

    // getters
    const isAuthenticated = computed(() => Boolean(token.value && user.value));
    const role = computed(() => user.value?.role ?? null);
    const isAdmin = computed(() => user.value?.role === 'admin');
    const isOwner = computed(() => user.value?.role === 'owner');

    // actions
    function clear() {
        token.value = null;
        user.value = null;
        localStorage.removeItem(TOKEN_KEY);
    }

    function start(data) {
        token.value = data.token;
        user.value = data;
        ready.value = true;
        localStorage.setItem(TOKEN_KEY, data.token);
        return data.user;
    }

    async function init() {
        if (ready.value) return;
        if (token.value) {
            try {
                user.value = await authService.me();
            } catch {
                clear();
            }
        }

        ready.value = true;
    }

    async function login(payload) {
        return start(await authService.login(payload));
    }

    async function adminLogin(payload) {
        return start(await authService.adminLogin(payload));
    }

    async function register(payload) {
        return start(await authService.register(payload));
    }

    async function logout() {
        try {
            await authService.logout();
        } catch {
            user.value = null;
        }

        clear();
    }

    return {
        // state
        token,
        user,
        ready,

        // getters
        isAuthenticated,
        role,
        isAdmin,
        isOwner,

        // actions
        init,
        login,
        adminLogin,
        register,
        start,
        logout,
        clear,
    };
});
