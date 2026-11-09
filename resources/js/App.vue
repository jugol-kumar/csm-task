<script setup>
import {useAuthStore} from "@/stores/auth.js";
import {onMounted} from "vue";
import Toast from "@/components/Toast.vue";

const authStore = useAuthStore()

onMounted(() => authStore.init())
</script>

<template>
    <RouterView/>
    <Toast/>
</template>

<style scoped>

</style>
