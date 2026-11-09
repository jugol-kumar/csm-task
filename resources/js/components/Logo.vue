<script setup>
import {app_name} from "../utils/config.js";

defineProps({ to: { type: String, default: '/' } });
</script>

<template>
    <RouterLink :to="to" class="group inline-flex items-center gap-2.5 transition-transform active:scale-95">
        <span class="flex h-5 w-5 items-center justify-center rounded-md bg-gradient-to-br from-amber-400 via-orange-500 to-rose-500 shadow-sm transition-shadow group-hover:shadow-md">
            <span class="h-2 w-2 rounded-sm bg-white/90"></span>
        </span>
        <span class="font-mono text-xl font-bold tracking-tight text-slate-800 transition-colors group-hover:bg-gradient-to-r group-hover:from-amber-500 group-hover:to-orange-600 group-hover:bg-clip-text group-hover:text-transparent">
            {{ app_name }}
        </span>
    </RouterLink>
</template>
