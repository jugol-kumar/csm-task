
<script setup>
import { reactive } from 'vue';
import { userService } from '@/services/user.service';
import { useFormSubmit } from '@/composables/useFormSubmit';
import { useToast } from '@/composables/useToast';
import FormField from '@/components/FormField.vue';

const props = defineProps({
    owner: { type: Object, default: null }
});

const emit = defineEmits(['saved', 'cancel']);

const toast = useToast();
const { busy, run, err } = useFormSubmit();

const form = reactive({
    name: props.owner?.name ?? '',
    email: props.owner?.email ?? '',
    password: '',
});

async function submit() {
    const payload = {
        name: form.name,
        email: form.email,
        ...(form.password ? { password: form.password } : {}),
    };

    const res = await run(() =>userService.update(props.owner.id, payload));

    if (res) {
        toast.success('User updated successfully');
        emit('saved');
    }
}
</script>

<template>
    <form class="space-y-5" @submit.prevent="submit">

        <FormField label="Full name" :error="err('name')">
            <input
                v-model="form.name"
                type="text"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition-colors hover:border-slate-300 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                placeholder="Enter full name"
                required
            />
        </FormField>

        <FormField label="Email address" :error="err('email')">
            <input
                v-model="form.email"
                type="email"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition-colors hover:border-slate-300 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                placeholder="user@example.com"
                required
            />
        </FormField>

        <!-- Password -->
        <FormField
            label="Password"
            :error="err('password')"
            :hint="owner
                ? 'Leave empty to keep the current password.'
                : 'At least 8 characters required.'"
        >
            <input
                v-model="form.password"
                type="password"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition-colors hover:border-slate-300 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                autocomplete="new-password"
                placeholder="••••••••"
                :required="!owner"
            />
        </FormField>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-4">
            <button
                type="button"
                class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 active:scale-95 disabled:opacity-50"
                @click="emit('cancel')"
                :disabled="busy"
            >
                Cancel
            </button>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99] disabled:opacity-70"
                :disabled="busy"
            >
                <span v-if="busy" class="inline-flex items-center gap-2">
                    <svg
                        class="h-4 w-4 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        />
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l-2.647z"
                        />
                    </svg>
                    Updating…
                </span>

                <span v-else>
                    Update User
                </span>
            </button>
        </div>

    </form>
</template>
