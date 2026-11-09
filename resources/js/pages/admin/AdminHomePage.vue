<script setup>
import { computed, onMounted, ref } from 'vue';
import { dashboardService } from '@/services/dashboard.service';
import { useToast } from '@/composables/useToast';
import PageHeading from '@/components/PageHeading.vue';
import CounterCard from '@/components/CounterCard.vue';
import ModuleCard from "@/components/ModuleCard.vue";

const toast = useToast();
const counters = ref(null);
const loading = ref(true);

onMounted(async () => {
    try {
        counters.value = (await dashboardService.admin()).counters;
    } catch {
        toast.error('Could not load the counters.');
    } finally {
        loading.value = false;
    }
});

const strip = computed(() => [
    { label: 'Shops', value: counters.value?.shops },
    { label: 'Shop owners', value: counters.value?.owners },
    { label: 'Products', value: counters.value?.products },
    { label: 'Shop listings', value: counters.value?.listings, note: 'products placed in shops' },
    { label: 'Customers', value: counters.value?.customers },
]);

const modules = [
    { no: '01', title: 'Shops', text: 'Create, edit and close shops. Link an owner to each.', to: '/admin/shops' },
    { no: '02', title: 'Products', text: 'Manage the shared catalog that every shop picks from.', to: '/admin/products' },
    { no: '03', title: 'Shop owners', text: 'Create owner accounts and connect them to shops.', to: '/admin/owners' },
];
</script>

<template>
    <div class="space-y-10">
        <!-- Header Section -->
        <PageHeading eyebrow="Control room" title="Admin home" />

        <!-- Counter Overview Section -->
        <section class="space-y-3">
            <h2 class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">
                Right now
            </h2>
            <CounterCard :items="strip" :loading="loading" />
        </section>

        <!-- Navigation Modules Section -->
        <section class="space-y-3">
            <h2 class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">
                Manage
            </h2>
            <ModuleCard :modules="modules" />
        </section>
    </div>
</template>
