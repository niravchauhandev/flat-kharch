<template>
  <AppLayout>
    <div class="space-y-6">
      <!-- Welcome Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-white tracking-tight">
            Kem Chho, {{ authStore.userName }} 👋
          </h1>
          <p class="text-xs sm:text-sm text-slate-400 mt-1">
            Here is what's happening with Flat B-402 expenses this month.
          </p>
        </div>

        <div class="flex items-center gap-2.5">
          <router-link
            to="/spa/members"
            class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700/80 hover:bg-slate-800 text-slate-200 text-xs font-semibold flex items-center gap-2 transition"
          >
            <Users class="w-3.5 h-3.5 text-slate-400" />
            <span>Manage Flatmates</span>
          </router-link>

          <button 
            @click="openAddExpenseNotice"
            class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white text-xs font-semibold shadow-lg shadow-emerald-500/20 flex items-center gap-1.5 transition"
          >
            <Plus class="w-4 h-4" />
            <span>+ Add Expense</span>
          </button>
        </div>
      </div>

      <!-- Key Metrics KPI Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Kharch -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-xl relative overflow-hidden">
          <div class="flex items-center justify-between text-slate-400 text-xs font-medium">
            <span>Total Kharch (This Month)</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
              <Receipt class="w-4 h-4" />
            </div>
          </div>
          <div class="mt-3">
            <span class="text-2xl font-extrabold text-white">₹18,450</span>
            <span class="block text-[11px] text-emerald-400 mt-1 flex items-center gap-1">
              <TrendingDown class="w-3 h-3" /> 8% lower than last month
            </span>
          </div>
        </div>

        <!-- Monthly Pool Balance -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-xl relative overflow-hidden">
          <div class="flex items-center justify-between text-slate-400 text-xs font-medium">
            <span>Pool Balance Remaining</span>
            <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-400 flex items-center justify-center">
              <WalletCards class="w-4 h-4" />
            </div>
          </div>
          <div class="mt-3">
            <span class="text-2xl font-extrabold text-teal-400">₹5,550</span>
            <span class="block text-[11px] text-slate-400 mt-1">
              Total Pool Fund: ₹24,000
            </span>
          </div>
        </div>

        <!-- My Share -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-xl relative overflow-hidden">
          <div class="flex items-center justify-between text-slate-400 text-xs font-medium">
            <span>My Personal Share</span>
            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center">
              <PieChart class="w-4 h-4" />
            </div>
          </div>
          <div class="mt-3">
            <span class="text-2xl font-extrabold text-white">₹3,075</span>
            <span class="block text-[11px] text-cyan-400 mt-1">
              Based on equal split (6 members)
            </span>
          </div>
        </div>

        <!-- Active Roommates -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-xl relative overflow-hidden">
          <div class="flex items-center justify-between text-slate-400 text-xs font-medium">
            <span>Active Flatmates</span>
            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
              <Users class="w-4 h-4" />
            </div>
          </div>
          <div class="mt-3">
            <span class="text-2xl font-extrabold text-white">{{ membersCount }}</span>
            <span class="block text-[11px] text-indigo-300 mt-1">
              All active for meal expenses
            </span>
          </div>
        </div>
      </div>

      <!-- Main Grid: Recent Expenses & Roommate Balance -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Expenses Feed (2 cols) -->
        <div class="lg:col-span-2 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-xl p-5">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h2 class="text-base font-bold text-white">Recent Daily Kharch</h2>
              <p class="text-xs text-slate-400">Live transactions recorded by roommates</p>
            </div>
            <span class="text-xs text-emerald-400 font-semibold cursor-pointer hover:underline">
              View All
            </span>
          </div>

          <div class="divide-y divide-slate-800/80">
            <div v-for="expense in recentExpenses" :key="expense.id" class="py-3 flex items-center justify-between gap-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-lg">
                  {{ expense.icon }}
                </div>
                <div>
                  <h3 class="text-sm font-semibold text-slate-100">{{ expense.title }}</h3>
                  <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                    <span>Paid by <strong class="text-slate-200">{{ expense.paid_by }}</strong></span>
                    <span>•</span>
                    <span>{{ expense.date }}</span>
                  </div>
                </div>
              </div>

              <div class="text-right">
                <span class="text-sm font-bold text-emerald-400">₹{{ expense.amount }}</span>
                <span class="block text-[10px] text-slate-400">{{ expense.split_info }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Category Breakdown & WhatsApp Share (1 col) -->
        <div class="space-y-6">
          <div class="rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-xl p-5">
            <h2 class="text-base font-bold text-white mb-1">Expense Categories</h2>
            <p class="text-xs text-slate-400 mb-4">Monthly distribution overview</p>

            <div class="space-y-3">
              <div v-for="cat in categories" :key="cat.name">
                <div class="flex items-center justify-between text-xs mb-1">
                  <span class="text-slate-300 font-medium">{{ cat.name }}</span>
                  <span class="text-slate-400 font-semibold">₹{{ cat.amount }} ({{ cat.pct }}%)</span>
                </div>
                <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                  <div 
                    class="h-full rounded-full transition-all duration-500" 
                    :class="cat.colorClass" 
                    :style="{ width: cat.pct + '%' }"
                  ></div>
                </div>
              </div>
            </div>

            <!-- WhatsApp Share Quick Action -->
            <div class="mt-6 pt-5 border-t border-slate-800">
              <button 
                @click="shareOnWhatsApp"
                class="w-full py-2.5 px-4 rounded-xl bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/40 text-emerald-300 font-semibold text-xs flex items-center justify-center gap-2 transition"
              >
                <span>📲 Share Summary on WhatsApp</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/components/layout/AppLayout.vue';
import { useAuthStore } from '@/stores/auth';
import apiClient from '@/api/axios';
import { 
  Users, 
  Plus, 
  Receipt, 
  WalletCards, 
  PieChart, 
  TrendingDown 
} from 'lucide-vue-next';

const authStore = useAuthStore();
const membersCount = ref(6);

const recentExpenses = ref([
  { id: 1, title: 'Supermarket Grocery & Oil', amount: 3450, paid_by: 'Bhavik', date: 'Today, 2:30 PM', icon: '🛒', split_info: 'Split between 6' },
  { id: 2, title: 'Amul Milk & Curd (Weekly)', amount: 620, paid_by: 'Ketan', date: 'Yesterday', icon: '🥛', split_info: 'Split between 6' },
  { id: 3, title: 'Bharat Gas Cylinder refill', amount: 950, paid_by: 'Mehul', date: '04 Oct 2026', icon: '🔥', split_info: 'Split between 6' },
  { id: 4, title: 'Vegetables & Fruits (Sardar Market)', amount: 840, paid_by: 'Darshan', date: '02 Oct 2026', icon: '🥬', split_info: 'Split between 6' },
  { id: 5, title: 'WiFi High Speed Internet (Airtel)', amount: 1180, paid_by: 'Bhavik', date: '01 Oct 2026', icon: '🌐', split_info: 'Split between 6' },
]);

const categories = ref([
  { name: 'Grocery & Provisions', amount: 7200, pct: 39, colorClass: 'bg-emerald-500' },
  { name: 'Vegetables & Milk', amount: 4350, pct: 24, colorClass: 'bg-teal-400' },
  { name: 'Gas & Electricity Bills', amount: 3800, pct: 21, colorClass: 'bg-cyan-400' },
  { name: 'Maid & Cleaning Supplies', amount: 3100, pct: 16, colorClass: 'bg-indigo-400' },
]);

const openAddExpenseNotice = () => {
  alert('Expense module API is active. You can add expenses and split directly!');
};

const shareOnWhatsApp = () => {
  const text = encodeURIComponent(
    `🏠 Flat B-402 October Expense Summary:\n` +
    `💰 Total Kharch: ₹18,450\n` +
    `🏦 Pool Remaining: ₹5,550\n` +
    `👤 Per Head Share: ₹3,075\n` +
    `Please check Flat-Kharch app for settlement details.`
  );
  window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
};

onMounted(async () => {
  try {
    const res = await apiClient.get('/members');
    if (res.data?.success && res.data.data?.length) {
      membersCount.value = res.data.data.length;
    }
  } catch (err) {
    // fallback to demo count
  }
});
</script>
