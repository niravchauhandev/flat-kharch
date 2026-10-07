<template>
  <div id="flat-kharch-app" class="min-h-screen bg-slate-950 font-['Plus_Jakarta_Sans',sans-serif]">
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

onMounted(() => {
  if (authStore.token) {
    authStore.fetchUser();
  }
});
</script>
