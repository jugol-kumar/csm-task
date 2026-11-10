<script setup>
import { userService } from '@/services/user.service';
import { useCrudPage } from '@/composables/useCrudPage';
import PageHeading from '@/components/PageHeading.vue';
import SearchBar from '@/components/SearchBar.vue';
import Pagination from '@/components/Pagination.vue';
import EmptyState from '@/components/EmptyState.vue';
import BaseModal from '@/components/BaseModal.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import UserForm from '@/components/UserForm.vue';

const {
    items,
    meta,
    loading,
    query,
    setPage,
    modal,
    confirm,
    openForm,
    closeForm,
    saved,
    askRemove,
    cancelRemove,
    confirmRemove,
} = useCrudPage({ list: userService.list, remove: userService.remove, noun: 'User' });

function getRoleBadgeClass(role) {
    switch (role) {
        case 'admin':
            return 'bg-purple-50 text-purple-700 border-purple-200';
        case 'owner':
            return 'bg-orange-50 text-orange-700 border-orange-200';
        case 'customer':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        default:
            return 'bg-slate-50 text-slate-700 border-slate-200';
    }
}
</script>

<template>
    <div class="space-y-6">
        <PageHeading eyebrow="Module 03" title="Users" back="/admin" back-label="Admin home">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                @click="openForm()"
            >
                <span>+</span> New user
            </button>
        </PageHeading>

        <div class="max-w-sm">
            <SearchBar v-model="query.search" placeholder="Search by name or email..." />
        </div>

        <div v-if="loading" class="py-12 text-center font-mono text-xs text-slate-400">
            Loading users…
        </div>

        <div v-if="items.length" class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full min-w-[45rem] text-left text-sm text-slate-700">
                <thead class="border-b border-slate-200 bg-slate-50 font-mono text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-5 py-3.5">User</th>
                    <th class="px-5 py-3.5">Role</th>
                    <th class="px-5 py-3.5">Store / Shop</th>
                    <th class="px-5 py-3.5">Created</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <tr v-for="user in items" :key="user.id" class="transition-colors hover:bg-slate-50/60">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 font-bold text-white shadow-sm">
                                <span class="text-xs uppercase">{{ user.name?.charAt(0) || 'U' }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ user.name }}</p>
                                <p class="text-xs text-slate-400 font-mono">{{ user.email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 font-mono text-[11px] font-semibold border capitalize', getRoleBadgeClass(user.role)]">
                            {{ user.role }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div v-if="user.store" class="flex items-center gap-1.5 font-medium text-slate-800">
                            <span class="text-sm">🏪</span>
                            <span>{{ user.store.name }}</span>
                            <span class="font-mono text-[10px] text-slate-400">(/{{ user.store.slug }})</span>
                        </div>
                        <span v-else class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 font-mono text-[11px] font-medium text-slate-500 border border-slate-200">
                            No store assigned
                        </span>
                    </td>
                    <td class="px-5 py-3.5 font-mono text-xs text-slate-400">
                        {{ user.created_at?.slice(0, 10) || 'N/A' }}
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-orange-500 hover:text-orange-600 active:scale-95"
                                @click="openForm(user)"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-sm transition-all duration-200 hover:bg-rose-50 active:scale-95"
                                @click="askRemove(user)"
                            >
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <EmptyState
            v-else-if="!loading"
            title="No users yet"
            text="Create a user account, then assign roles or link it to a shop."
        >
            <button
                type="button"
                class="mt-3 inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                @click="openForm()"
            >
                New user
            </button>
        </EmptyState>

        <div class="pt-4">
            <Pagination :meta="meta" @change="setPage" />
        </div>

        <BaseModal :open="modal.open" :title="modal.record ? 'Edit user' : 'New user'" @close="closeForm">
            <UserForm :owner="modal.record" @saved="saved" @cancel="closeForm" />
        </BaseModal>

        <ConfirmDialog
            :open="confirm.open"
            title="Delete user"
            :message="`Delete ${confirm.record?.name}? Their shop stays, but loses its assigned owner.`"
            :busy="confirm.busy"
            @confirm="confirmRemove"
            @cancel="cancelRemove"
        />
    </div>
</template>
