<script setup>
import { reactive, ref } from 'vue';
import { storeService } from '@/services/store.service.js';
import { useFormSubmit } from '@/composables/useFormSubmit';
import { useToast } from '@/composables/useToast';
import FormField from '@/components/FormField.vue';

const props = defineProps({ shop: { type: Object, default: null } });
const emit = defineEmits(['saved', 'cancel']);

const toast = useToast();
const { busy, run, err } = useFormSubmit();

const currentStep = ref(1);

const form = reactive({
    owner_name: '',
    owner_email: '',
    owner_password: '',

    name: props.shop?.name ?? '',
    description: props.shop?.description ?? '',
    logo: null,
    banner: null,
});

function handleLogo(event) {
    form.logo = event.target.files[0] || null;
}

function handleBanner(event) {
    form.banner = event.target.files[0] || null;
}

function nextStep() {
    currentStep.value = 2;
}

function prevStep() {
    currentStep.value = 1;
}

async function submit() {
    const formData = new FormData();

    Object.keys(form).forEach((key) => {
        if (form[key] !== null && form[key] !== undefined && form[key] !== '') {
            formData.append(key, form[key]);
        }
    });

    const res = await run(() =>
        props.shop
            ? storeService.update(props.shop.id, formData)
            : storeService.create(formData)
    );

    if (res) {
        toast.success(props.shop ? 'Shop updated successfully' : 'Shop & Owner created successfully');
        emit('saved');
    }
}
</script>

<template>
    <form class="space-y-5" @submit.prevent="submit">

        <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-4">
            <div class="flex items-center gap-3">
                <span :class="currentStep === 1 ? 'bg-orange-500 text-white' : 'bg-slate-100 text-slate-700'" class="flex h-7 w-7 items-center justify-center rounded-full font-mono text-xs font-bold">
                    1
                </span>
                <span class="text-sm font-medium" :class="currentStep === 1 ? 'text-slate-900 font-semibold' : 'text-slate-400'">
                    Owner Information
                </span>
            </div>
            <div class="h-px w-10 bg-slate-200"></div>
            <div class="flex items-center gap-3">
                <span :class="currentStep === 2 ? 'bg-orange-500 text-white' : 'bg-slate-100 text-slate-700'" class="flex h-7 w-7 items-center justify-center rounded-full font-mono text-xs font-bold">
                    2
                </span>
                <span class="text-sm font-medium" :class="currentStep === 2 ? 'text-slate-900 font-semibold' : 'text-slate-400'">
                    Shop Details & Media
                </span>
            </div>
        </div>

        <div v-show="currentStep === 1" class="space-y-4">
            <h3 class="font-mono text-xs font-semibold uppercase tracking-wider text-amber-600">Step 1 of 2: Owner Details</h3>

            <FormField label="Owner Name" :error="err('owner_name')">
                <input
                    v-model="form.owner_name"
                    type="text"
                    placeholder="John Doe"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                />
            </FormField>

            <FormField label="Owner Email" :error="err('owner_email')">
                <input
                    v-model="form.owner_email"
                    type="email"
                    placeholder="owner@example.com"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                />
            </FormField>

            <FormField label="Password" :error="err('owner_password')" hint="At least 8 characters.">
                <input
                    v-model="form.owner_password"
                    type="password"
                    placeholder="••••••••"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                />
            </FormField>
        </div>

        <div v-show="currentStep === 2" class="space-y-4">
            <h3 class="font-mono text-xs font-semibold uppercase tracking-wider text-amber-600">Step 2 of 2: Shop Details & Media</h3>

            <FormField label="Shop Name" :error="err('name')">
                <input
                    v-model="form.name"
                    type="text"
                    placeholder="Enter shop name"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                />
            </FormField>

            <FormField label="Description" :error="err('description')">
                <textarea
                    v-model="form.description"
                    rows="2"
                    placeholder="Brief description about the shop"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                ></textarea>
            </FormField>

            <div class="grid gap-4 sm:grid-cols-2 pt-2">
                <FormField label="Shop Logo" :error="err('logo')">
                    <input
                        type="file"
                        accept="image/*"
                        @change="handleLogo"
                        class="w-full text-xs text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-orange-50 file:px-4 file:py-2 file:font-semibold file:text-orange-600 hover:file:bg-orange-100 cursor-pointer"
                    />
                </FormField>

                <FormField label="Shop Banner" :error="err('banner')">
                    <input
                        type="file"
                        accept="image/*"
                        @change="handleBanner"
                        class="w-full text-xs text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-orange-50 file:px-4 file:py-2 file:font-semibold file:text-orange-600 hover:file:bg-orange-100 cursor-pointer"
                    />
                </FormField>
            </div>
        </div>

        <div class="flex items-center justify-between border-t border-slate-200 pt-4">
            <button
                type="button"
                class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 active:scale-[0.98]"
                @click="currentStep === 1 ? emit('cancel') : prevStep()"
            >
                {{ currentStep === 1 ? 'Cancel' : 'Back' }}
            </button>

            <div>
                <button
                    v-if="currentStep === 1"
                    type="button"
                    class="rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-5 py-2.5 font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                    @click="nextStep"
                >
                    Next: Shop Details →
                </button>

                <button
                    v-else
                    type="submit"
                    class="rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-6 py-2.5 font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99] disabled:opacity-70"
                    :disabled="busy"
                >
                    <span v-if="busy" class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving…
                    </span>
                    <span v-else>{{ shop ? 'Save changes' : 'Create Shop & Owner' }}</span>
                </button>
            </div>
        </div>
    </form>
</template>
