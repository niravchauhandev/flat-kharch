<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 flex">
    <!-- Sidebar for Desktop -->
    <aside 
      :class="[
        'fixed inset-y-0 left-0 z-40 w-64 bg-slate-900/95 backdrop-blur-xl border-r border-slate-800/80 transition-transform duration-300 md:translate-x-0 flex flex-col justify-between',
        isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
      ]"
    >
      <div>
        <!-- Logo & Branding -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800/80">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 via-teal-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 text-white font-bold text-lg">
              ₹
            </div>
            <div>
              <span class="font-extrabold text-base tracking-tight bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">
                Flat-Kharch
              </span>
              <span class="block text-[10px] text-emerald-400 font-semibold tracking-wider uppercase">
                Smart Expense Hub
              </span>
            </div>
          </div>
          <!-- Close button for mobile -->
          <button 
            @click="isMobileMenuOpen = false" 
            class="md:hidden text-slate-400 hover:text-white"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Navigation Links -->
        <nav class="p-4 space-y-1.5">
          <router-link
            to="/spa/dashboard"
            @click="isMobileMenuOpen = false"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200"
            :class="isActive('/spa/dashboard') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60'"
          >
            <LayoutDashboard class="w-4 h-4" />
            <span>Dashboard</span>
          </router-link>

          <router-link
            to="/spa/members"
            @click="isMobileMenuOpen = false"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200"
            :class="isActive('/spa/members') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60'"
          >
            <Users class="w-4 h-4" />
            <span>Flat Members</span>
          </router-link>

          <router-link
            to="/spa/expenses"
            @click="isMobileMenuOpen = false"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200"
            :class="isActive('/spa/expenses') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60'"
          >
            <Receipt class="w-4 h-4" />
            <span>Daily Expenses</span>
            <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-semibold">Live</span>
          </router-link>

          <router-link
            to="/spa/pool"
            @click="isMobileMenuOpen = false"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200"
            :class="isActive('/spa/pool') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60'"
          >
            <WalletCards class="w-4 h-4" />
            <span>Monthly Pool</span>
          </router-link>

          <router-link
            to="/spa/settlements"
            @click="isMobileMenuOpen = false"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200"
            :class="isActive('/spa/settlements') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60'"
          >
            <Calculator class="w-4 h-4" />
            <span>Settlement</span>
          </router-link>
        </nav>
      </div>

      <!-- User Profile & Logout Bottom Section -->
      <div class="p-4 border-t border-slate-800/80 bg-slate-900/60">
        <div class="flex items-center justify-between gap-3">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-white text-sm shrink-0 overflow-hidden ring-2 ring-emerald-500/30">
              <img v-if="authStore.userAvatar" :src="authStore.userAvatar" class="w-full h-full object-cover" />
              <span v-else>{{ (authStore.userName || 'U')[0].toUpperCase() }}</span>
            </div>
            <div class="min-w-0">
              <p class="text-xs font-semibold text-slate-200 truncate">{{ authStore.userName }}</p>
              <span class="inline-block text-[10px] px-1.5 py-0.2 rounded-full font-medium"
                :class="authStore.isAdmin ? 'bg-amber-500/20 text-amber-300' : 'bg-slate-700 text-slate-300'"
              >
                {{ authStore.isAdmin ? 'Admin' : 'Member' }}
              </span>
            </div>
          </div>

          <button 
            @click="handleLogout"
            title="Log Out"
            class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors"
          >
            <LogOut class="w-4 h-4" />
          </button>
        </div>
      </div>
    </aside>

    <!-- Overlay backdrop for mobile -->
    <div 
      v-if="isMobileMenuOpen" 
      @click="isMobileMenuOpen = false" 
      class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 md:hidden"
    ></div>

    <!-- Main Content Area -->
    <div class="flex-1 md:pl-64 flex flex-col min-w-0">
      <!-- Top Bar -->
      <header class="h-16 sticky top-0 z-20 bg-slate-950/80 backdrop-blur-md border-b border-slate-800/80 px-4 md:px-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <button 
            @click="isMobileMenuOpen = true" 
            class="md:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800"
          >
            <Menu class="w-5 h-5" />
          </button>
          <div class="flex items-center gap-2 text-xs md:text-sm text-slate-400">
            <span>Flat No: <strong class="text-emerald-400 font-medium">B-402, Shivalik</strong></span>
            <span>•</span>
            <span class="hidden sm:inline">Active Month: <strong class="text-slate-200">October 2026</strong></span>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <!-- Install App Button (Shows when browser supports PWA installation) -->
          <button 
            v-if="deferredPrompt"
            @click="installPwa"
            class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-slate-950 font-bold text-xs flex items-center gap-1.5 shadow-md shadow-emerald-500/20 hover:scale-105 active:scale-95 transition-all"
          >
            <Smartphone class="w-3.5 h-3.5" />
            <span>Install App</span>
          </button>

          <div class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            REST API Connected
          </div>
        </div>
      </header>

      <!-- Page Outlet -->
      <main class="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { 
  LayoutDashboard, 
  Users, 
  Receipt, 
  WalletCards, 
  Calculator, 
  LogOut, 
  Menu, 
  X,
  Smartphone
} from 'lucide-vue-next';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const isMobileMenuOpen = ref(false);
const deferredPrompt = ref(null);

const isActive = (path) => route.path === path;

const handleLogout = async () => {
  await authStore.logout();
  router.push('/spa/login');
};

const handleBeforeInstallPrompt = (e) => {
  e.preventDefault();
  deferredPrompt.value = e;
};

const handleAppInstalled = () => {
  deferredPrompt.value = null;
};

const installPwa = async () => {
  if (!deferredPrompt.value) return;
  deferredPrompt.value.prompt();
  const { outcome } = await deferredPrompt.value.userChoice;
  if (outcome === 'accepted') {
    deferredPrompt.value = null;
  }
};

onMounted(() => {
  window.addEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
  window.addEventListener('appinstalled', handleAppInstalled);
});

onUnmounted(() => {
  window.removeEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
  window.removeEventListener('appinstalled', handleAppInstalled);
});
</script>
