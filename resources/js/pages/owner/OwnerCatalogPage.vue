<script setup>
import { onMounted, reactive } from 'vue';
import { catalogService } from '@/services/catalog.service';
import { listingService } from '@/services/listing.service';
import { usePagedList } from '@/composables/usePagedList';
import { useToast } from '@/composables/useToast';
import PageHeading from '@/components/PageHeading.vue';
import SearchBar from '@/components/SearchBar.vue';
import Pagination from '@/components/Pagination.vue';
import EmptyState from '@/components/EmptyState.vue';
import BaseModal from '@/components/BaseModal.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import CatalogGrid from '@/components/CatalogGrid.vue';
import AddToShopForm from '@/components/AddToShopForm.vue';
import ProductForm from '@/components/ProductForm.vue';

const toast = useToast();
const filters = reactive({ scope: 'all' });
const { items, meta, loading, query, load, setPage } = usePagedList(catalogService.list, filters);

const scopes = [
    { value: 'all', label: 'Available In Store' },
    { value: 'available', label: 'Not In My Shop' },
    { value: 'mine', label: 'Added By Me' },
];

const addModal = reactive({ open: false, product: null });
const editModal = reactive({ open: false, product: null });
const createModal = reactive({ open: false });
const confirm = reactive({ open: false, product: null, busy: false });
const detachConfirm = reactive({ open: false, product: null, busy: false });

onMounted(load);

function openAdd(product) {
    addModal.product = product;
    addModal.open = true;
}

function openEdit(product) {
    editModal.product = product;
    editModal.open = true;
}

function askRemove(product) {
    confirm.product = product;
    confirm.open = true;
}

function askDetach(product) {
    detachConfirm.product = product;
    detachConfirm.open = true;
}

async function detach() {
    detachConfirm.busy = true;
    try {
        const product = detachConfirm.product;
        await listingService.remove(product.id);
        toast.success(`${product.name} removed from your shop`);
        detachConfirm.open = false;
        await load();
    } catch (error) {
        toast.error(error.response?.data?.message ?? 'Could not remove product from shop.');
    } finally {
        detachConfirm.busy = false;
    }
}

async function added() {
    addModal.open = false;
    await load();
}

async function edited() {
    editModal.open = false;
    await load();
}

async function created() {
    createModal.open = false;
    toast.success('Product created successfully');
    await load();
}

function saver(payload) {
    return catalogService.updateProduct(editModal.product.id, payload);
}

function creator(payload) {
    return catalogService.createProduct(payload);
}

async function remove() {
    confirm.busy = true;
    try {
        await catalogService.removeProduct(confirm.product.product_id);
        toast.success('Product deleted');
        confirm.open = false;
        await load();
    } catch (error) {
        toast.error(error.response?.data?.message ?? 'Could not delete the product.');
    } finally {
        confirm.busy = false;
    }
}
</script>

<template>
    <div class="space-y-6">
        <PageHeading eyebrow="Shared catalog" title="Catalog" back="/owner" back-label="Overview">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                @click="createModal.open = true"
            >
                <span>+</span> New product
            </button>
        </PageHeading>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-md flex-1">
                <SearchBar v-model="query.search" placeholder="Search the catalog..." />
            </div>

            <div class="inline-flex overflow-hidden rounded-xl border border-slate-200 bg-white p-1 shadow-sm font-mono text-xs uppercase tracking-wider">
                <button
                    v-for="scope in scopes"
                    :key="scope.value"
                    type="button"
                    class="rounded-lg px-3.5 py-2 font-semibold transition-all duration-200"
                    :class="
                        filters.scope === scope.value
                            ? 'bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 text-white shadow-sm'
                            : 'text-slate-600 hover:text-orange-600 hover:bg-slate-50'
                    "
                    @click="filters.scope = scope.value"
                >
                    {{ scope.label }}
                </button>
            </div>
        </div>

        <div v-if="loading" class="py-12 text-center font-mono text-xs text-slate-400">
            Loading catalog products…
        </div>

        <CatalogGrid
            v-if="items.length"
            :items="items"
            @add="openAdd"
            @edit="openEdit"
            @remove="askRemove"
            @detach="askDetach"
        />

        <EmptyState
            v-else-if="!loading"
            title="Nothing in this view"
            text="Try another filter, or add a product of your own."
        >
            <button
                type="button"
                class="mt-3 inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                @click="createModal.open = true"
            >
                New product
            </button>
        </EmptyState>

        <div class="pt-4">
            <Pagination :meta="meta" @change="setPage" />
        </div>

        <BaseModal :open="addModal.open" title="Add to my shop" @close="addModal.open = false">
            <AddToShopForm :product="addModal.product" @saved="added" @cancel="addModal.open = false" />
        </BaseModal>

        <BaseModal :open="createModal.open" title="New catalog product" @close="createModal.open = false">
            <ProductForm :saver="creator" @saved="created" @cancel="createModal.open = false" />
        </BaseModal>

        <BaseModal :open="editModal.open" title="Edit catalog product" @close="editModal.open = false">
            <ProductForm :product="editModal.product" :saver="saver" @saved="edited" @cancel="editModal.open = false" />
        </BaseModal>

        <ConfirmDialog
            :open="confirm.open"
            title="Delete product"
            :message="`Delete ${confirm.product?.name} from the catalog? This also removes it from your shop.`"
            :busy="confirm.busy"
            @confirm="remove"
            @cancel="confirm.open = false"
        />

        <ConfirmDialog
            :open="detachConfirm.open"
            title="Remove from shop"
            :message="`Remove ${detachConfirm.product?.name} from your shop? It will stay in the catalog.`"
            :busy="detachConfirm.busy"
            @confirm="detach"
            @cancel="detachConfirm.open = false"
        />
    </div>
</template>
