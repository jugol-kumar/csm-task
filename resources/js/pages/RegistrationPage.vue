<script setup>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { authService } from '@/services/auth.service';
import FormField from "@/components/FormField.vue";
import { useFormSubmit } from "@/composables/useFormSubmit.js";

const emit = defineEmits(['success']);
const router = useRouter();

const form = reactive({ name: '', email: '', password: '', password_confirmation: '' });
const { busy, run, err } = useFormSubmit();

async function submit() {
    const data = await run(() => authService.register(form));

    if (data) {
        emit('success', data);
        router.push('/');
    }
}
</script>

<template>
    <!-- Centered Card Container -->
    <div class="flex flex-1 items-center justify-center py-6 sm:py-12">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

            <!-- Header Section -->
            <div class="mb-8 text-center">
                <span class="inline-block text-xs font-mono font-semibold uppercase tracking-widest text-amber-600">
                    Get Started
                </span>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Create an account
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Join us and manage your shop or explore catalog
                </p>
            </div>

            <!-- Form -->
            <form class="space-y-4" @submit.prevent="submit">
                <FormField label="Full Name" :error="err('name')">
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="John Doe"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                        autocomplete="name"
                        required
                    />
                </FormField>

                <FormField label="Email" :error="err('email')">
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="name@example.com"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                        autocomplete="email"
                        required
                    />
                </FormField>

                <FormField label="Password" :error="err('password')" hint="At least 8 characters.">
                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="••••••••"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                        autocomplete="new-password"
                        required
                    />
                </FormField>

                <FormField label="Confirm Password" :error="err('password_confirmation')">
                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        placeholder="••••••••"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                        autocomplete="new-password"
                        required
                    />
                </FormField>

                <!-- Submit Button with Orange Gradient -->
                <button
                    type="submit"
                    class="mt-2 w-full rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 py-3 font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99] disabled:opacity-70"
                    :disabled="busy"
                >
                    <span v-if="busy" class="inline-flex items-center justify-center gap-2">
                        <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creating…
                    </span>
                    <span v-else>Create account</span>
                </button>
            </form>

            <!-- Footer Link to Login -->
            <div class="mt-6 text-center text-xs text-slate-500">
                Already have an account?
                <RouterLink to="/login" class="font-semibold text-orange-600 hover:underline">
                    Sign in here
                </RouterLink>
            </div>

        </div>
    </div>
</template>
