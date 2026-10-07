<template>
  <AppLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-white tracking-tight">Flat Members</h1>
          <p class="text-xs sm:text-sm text-slate-400 mt-1">
            Manage roommates, login access, and role permissions
          </p>
        </div>

        <button
          @click="showAddModal = true"
          class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white text-xs font-semibold shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-1.5 transition"
        >
          <UserPlus class="w-4 h-4" />
          <span>+ Add New Flatmate</span>
        </button>
      </div>

      <!-- Search & Filters -->
      <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by name, phone or email..."
            class="w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-500 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
          />
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="py-12 flex flex-col items-center justify-center text-slate-400">
        <Loader2 class="w-8 h-8 animate-spin text-emerald-400 mb-2" />
        <span class="text-xs">Loading members from REST API...</span>
      </div>

      <!-- Members Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="member in filteredMembers"
          :key="member.id"
          class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-xl hover:border-slate-700/80 transition flex flex-col justify-between"
        >
          <div>
            <div class="flex items-start justify-between gap-3">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 font-bold text-lg overflow-hidden shrink-0">
                  <img v-if="member.avatar_url" :src="member.avatar_url" class="w-full h-full object-cover" />
                  <span v-else>{{ member.name[0].toUpperCase() }}</span>
                </div>
                <div>
                  <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    {{ member.name }}
                    <span 
                      class="text-[10px] px-2 py-0.5 rounded-full font-semibold"
                      :class="member.role === 1 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-slate-800 text-slate-300'"
                    >
                      {{ member.role === 1 ? 'Admin' : 'Member' }}
                    </span>
                  </h3>
                  <p class="text-xs text-slate-400 mt-0.5">{{ member.phone || 'No phone' }}</p>
                </div>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/60 space-y-1.5 text-xs text-slate-400">
              <div class="flex items-center justify-between">
                <span>Email:</span>
                <span class="text-slate-200 font-medium truncate max-w-[180px]">{{ member.email }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span>Status:</span>
                <span 
                  class="inline-flex items-center gap-1.5 font-semibold text-[11px]"
                  :class="member.status === 1 ? 'text-emerald-400' : 'text-rose-400'"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="member.status === 1 ? 'bg-emerald-400' : 'bg-rose-400'"></span>
                  {{ member.status === 1 ? 'Active' : 'Inactive' }}
                </span>
              </div>
            </div>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-800/60 flex items-center justify-between">
            <button
              @click="toggleStatus(member)"
              class="text-[11px] font-semibold transition"
              :class="member.status === 1 ? 'text-amber-400 hover:text-amber-300' : 'text-emerald-400 hover:text-emerald-300'"
            >
              {{ member.status === 1 ? 'Deactivate' : 'Activate' }}
            </button>
            <span class="text-[11px] text-slate-500">Joined: {{ member.created_at?.split(' ')[0] }}</span>
          </div>
        </div>
      </div>

      <!-- Add Member Modal -->
      <div v-if="showAddModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl relative">
          <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-white">Add New Flatmate</h2>
            <button @click="showAddModal = false" class="text-slate-400 hover:text-white">
              <X class="w-5 h-5" />
            </button>
          </div>

          <form @submit.prevent="createMember" class="space-y-3.5">
            <div>
              <label class="block text-xs font-medium text-slate-300 mb-1">Full Name</label>
              <input
                v-model="newMember.name"
                type="text"
                required
                placeholder="e.g. Rahul Sharma"
                class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-slate-100 text-xs focus:ring-2 focus:ring-emerald-500/40 outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-medium text-slate-300 mb-1">Email</label>
              <input
                v-model="newMember.email"
                type="email"
                required
                placeholder="rahul@example.com"
                class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-slate-100 text-xs focus:ring-2 focus:ring-emerald-500/40 outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-medium text-slate-300 mb-1">Mobile Number</label>
              <input
                v-model="newMember.phone"
                type="text"
                required
                placeholder="9876543210"
                class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-slate-100 text-xs focus:ring-2 focus:ring-emerald-500/40 outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-medium text-slate-300 mb-1">Password</label>
              <input
                v-model="newMember.password"
                type="password"
                required
                placeholder="At least 6 characters"
                class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-slate-100 text-xs focus:ring-2 focus:ring-emerald-500/40 outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-medium text-slate-300 mb-1">Role</label>
              <select
                v-model="newMember.role"
                class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-slate-100 text-xs focus:ring-2 focus:ring-emerald-500/40 outline-none"
              >
                <option :value="2">Flat Member</option>
                <option :value="1">Admin / Coordinator</option>
              </select>
            </div>

            <div class="pt-3 flex items-center justify-end gap-2.5">
              <button
                type="button"
                @click="showAddModal = false"
                class="px-4 py-2 rounded-xl text-xs text-slate-400 hover:text-white"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white font-semibold text-xs transition disabled:opacity-50"
              >
                {{ submitting ? 'Adding...' : 'Save Flatmate' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/components/layout/AppLayout.vue';
import apiClient from '@/api/axios';
import { UserPlus, Search, Loader2, X } from 'lucide-vue-next';

const members = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const showAddModal = ref(false);
const submitting = ref(false);

const newMember = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  role: 2,
  status: 1,
});

const fetchMembers = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/members');
    if (res.data?.success) {
      members.value = res.data.data;
    }
  } catch (err) {
    console.error('Failed to load members:', err);
  } finally {
    loading.value = false;
  }
};

const filteredMembers = computed(() => {
  if (!searchQuery.value) return members.value;
  const q = searchQuery.value.toLowerCase();
  return members.value.filter(m => 
    m.name?.toLowerCase().includes(q) ||
    m.email?.toLowerCase().includes(q) ||
    m.phone?.includes(q)
  );
});

const toggleStatus = async (member) => {
  try {
    const res = await apiClient.patch(`/members/${member.id}/toggle-status`);
    if (res.data?.success) {
      member.status = res.data.status;
    }
  } catch (err) {
    alert('Failed to update status');
  }
};

const createMember = async () => {
  submitting.value = true;
  try {
    const res = await apiClient.post('/members', newMember.value);
    if (res.data?.success) {
      showAddModal.value = false;
      newMember.value = { name: '', email: '', phone: '', password: '', role: 2, status: 1 };
      await fetchMembers();
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Error creating member');
  } finally {
    submitting.value = false;
  }
};

onMounted(() => {
  fetchMembers();
});
</script>
