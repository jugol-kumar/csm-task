<script setup>
import { computed, onMounted, ref } from 'vue';
import { dashboardService } from '@/services/dashboard.service';
import { useToast } from '@/composables/useToast';
import PageHeading from '@/components/PageHeading.vue';
import EmptyState from '@/components/EmptyState.vue';
import CounterStrip from '@/components/CounterCard.vue';

const toast = useToast();
const data = ref(null);
const loading = ref(true);

onMounted(async () => {
    try {
        data.value = await dashboardService.owner();
    } catch {
        toast.error('Could not load your overview.');
    } finally {
        loading.value = false;
    }
});

const strip = computed(() => [
    { label: 'In my shop', value: data.value?.counters?.listings },
    { label: 'Visible', value: data.value?.counters?.active, note: 'customers can see these' },
    { label: 'Catalog size', value: data.value?.counters?.catalog },
    { label: 'Not in my shop', value: data.value?.counters?.available, note: 'ready to add' },
    { label: 'Created by me', value: data.value?.counters?.mine },
]);

</script>

<template>
    <div>
        <p v-if="loading" class="font-mono text-xs text-mute">Loading…</p>

        <EmptyState
            v-else-if="data && !data.shop"
            title="No shop linked yet"
            text="An admin has to link a shop to your account before you can list products. Check back soon."
        />

        <template v-else-if="data">
            <PageHeading eyebrow="Your shop" :title="data.shop.name"/>

            <section>
                <h2 class="label">Right now</h2>
                <CounterStrip :items="strip" :loading="loading" />
            </section>
        </template>
    </div>
</template>
