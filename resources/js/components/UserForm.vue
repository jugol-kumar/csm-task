<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { userService } from '@/services/user.service';
import { useFormSubmit } from '@/composables/useFormSubmit';
import { useToast } from '@/composables/useToast';
import FormField from '@/components/FormField.vue';
import {storeService} from "@/services/store.service.js";

const props = defineProps({ owner: { type: Object, default: null } });
const emit = defineEmits(['saved', 'cancel']);

const toast = useToast();
const { busy, run, err } = useFormSubmit();

const form = reactive({
    name: props.owner?.name ?? '',
    email: props.owner?.email ?? '',
    role: props.owner?.role ?? 'owner',
    password: '',
    shop_id: props.owner?.shop?.id ?? '',
    create_new_store: false,
    new_store_name: '',
    new_store_slug: '',
});

const shops = ref([]);
const available = computed(() => shops.value.filter((shop) => !shop.owner_id || shop.owner_id === props.owner?.id));

watch(() => form.new_store_name, (val) => {
    if (form.create_new_store) {
        form.new_store_slug = val.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    }
});

onMounted(async () => {
    try {
        shops.value = (await storeService.list()).data;
    } catch {
        toast.error('Could not load shops.');
    }
});

async function submit() {
    const payload = {
        name: form.name,
        email: form.email,
        role: form.role,
        ...(form.password ? { password: form.password } : {}),
    };

    if (form.role === 'owner') {
        if (form.create_new_store) {
            payload.new_store = {
                name: form.new_store_name,
                slug: form.new_store_slug,
            };
        } else {
            payload.shop_id = form.shop_id || null;
        }
    }

    const res = await run(() => (props.owner ? userService.update(props.owner.id, payload) : userService.create(payload)));

    if (res) {
        toast.success(props.owner ? 'User updated successfully' : 'User created successfully');
        emit('saved');
    }
}
</script>

<template>
    <form class="space-y-5" @submit.prevent="submit">
        <FormField label="User Role" :error="err('role')">
            <div class="grid grid-cols-3 gap-2">
                <label
                    v-for="roleOption in ['admin', 'owner', 'customer']"
                    :key="roleOption"
                    class="flex cursor-pointer flex-col items-center justify-center rounded-xl border p-3 text-center transition-all"
                    :class="form.role === roleOption
                        ? 'border-orange-500 bg-orange-50/50 text-orange-600 ring-2 ring-orange-500/20 font-semibold'
                        : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300'"
                >
                    <input type="radio" v-model="form.role" :value="roleOption" class="sr-only" />
                    <span class="capitalize font-mono text-xs">{{ roleOption }}</span>
                </label>
            </div>
        </FormField>

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

        <FormField
            label="Password"
            :error="err('password')"
            :hint="owner ? 'Leave empty to keep the current password.' : 'At least 8 characters required.'"
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

        <div v-if="form.role === 'owner'" class="space-y-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="font-mono text-xs font-bold uppercase tracking-wider text-slate-700">Shop Assignment</span>
                <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-orange-600">
                    <input type="checkbox" v-model="form.create_new_store" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500/20" />
                    Create new store
                </label>
            </div>

            <FormField v-if="!form.create_new_store" label="Assigned shop" :error="err('shop_id')">
                <div class="relative">
                    <select
                        v-model="form.shop_id"
                        class="w-full appearance-none rounded-lg border border-slate-200 bg-white px-4 py-2.5 pr-10 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:border-slate-300 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                    >
                        <option value="">No shop assigned yet</option>
                        <option v-for="shop in available" :key="shop.id" :value="shop.id">{{ shop.name }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </FormField>

            <div v-else class="space-y-3 pt-1">
                <FormField label="New Store Name" :error="err('new_store.name')">
                    <input
                        v-model="form.new_store_name"
                        type="text"
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20"
                        placeholder="e.g. Acme Tech Store"
                        required
                    />
                </FormField>
                <FormField label="Store Slug" :error="err('new_store.slug')">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 font-mono text-xs text-slate-400">/shops/</span>
                        <input
                            v-model="form.new_store_slug"
                            type="text"
                            class="w-full rounded-lg border border-slate-200 bg-white py-2 pr-4 pl-20 font-mono text-xs text-slate-800 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20"
                            placeholder="acme-tech-store"
                            required
                        />
                    </div>
                </FormField>
            </div>
        </div>

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
                    <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving…
                </span>
                <span v-else>{{ owner ? 'Save changes' : 'Create user' }}</span>
            </button>
        </div>
    </form>
</template>
