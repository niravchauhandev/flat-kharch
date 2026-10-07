<template>
  <div class="min-h-screen bg-slate-950 flex flex-col justify-center items-center px-4 relative overflow-hidden">
    <!-- Ambient glowing backgrounds -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
      <!-- Brand Logo -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 via-teal-500 to-cyan-500 shadow-xl shadow-emerald-500/25 text-white font-extrabold text-2xl mb-4">
          ₹
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-white">Flat-Kharch</h1>
        <p class="text-sm text-slate-400 mt-1">Smart Roommate Expense & Pool Management</p>
      </div>

      <!-- Login Card -->
      <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl shadow-black/50">
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-white">Welcome Back</h2>
          <p class="text-xs text-slate-400 mt-0.5">Enter your Email or Mobile Number to access your account</p>
        </div>

        <!-- Error Alert -->
        <div v-if="errorMessage" class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-start gap-2.5">
          <AlertCircle class="w-4 h-4 shrink-0 mt-0.5" />
          <span>{{ errorMessage }}</span>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <!-- Login (Email / Phone) -->
          <div>
            <label class="block text-xs font-medium text-slate-300 mb-1.5">
              Email or Mobile Number
            </label>
            <div class="relative">
              <input
                v-model="form.login"
                type="text"
                required
                placeholder="e.g. admin@flatkharch.com or 9876543210"
                class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-700/80 rounded-xl text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
              />
            </div>
          </div>

          <!-- Password -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-xs font-medium text-slate-300">Password</label>
              <a href="#" class="text-[11px] text-emerald-400 hover:underline">Forgot password?</a>
            </div>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                placeholder="••••••••"
                class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-700/80 rounded-xl text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all pr-10"
              />
              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200"
              >
                <EyeOff v-if="showPassword" class="w-4 h-4" />
                <Eye v-else class="w-4 h-4" />
              </button>
            </div>
          </div>

          <!-- Remember me checkbox -->
          <div class="flex items-center gap-2 pt-1">
            <input 
              v-model="form.remember" 
              id="remember" 
              type="checkbox" 
              class="rounded bg-slate-950 border-slate-700 text-emerald-500 focus:ring-emerald-500/30 w-4 h-4"
            />
            <label for="remember" class="text-xs text-slate-400 select-none cursor-pointer">
              Remember this device
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full py-2.5 px-4 mt-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-semibold text-sm shadow-lg shadow-emerald-500/25 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <Loader2 v-if="loading" class="w-4 h-4 animate-spin" />
            <span>{{ loading ? 'Signing in via API...' : 'Sign In' }}</span>
          </button>
        </form>

        <!-- Demo Credentials Helper -->
        <div class="mt-6 pt-5 border-t border-slate-800 text-center">
          <p class="text-[11px] text-slate-500">
            Powered by Laravel REST API & Vue 3 SPA
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { Eye, EyeOff, AlertCircle, Loader2 } from 'lucide-vue-next';

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
  login: '',
  password: '',
  remember: false,
});

const showPassword = ref(false);
const loading = ref(false);
const errorMessage = ref('');

const handleSubmit = async () => {
  loading.value = true;
  errorMessage.value = '';

  const res = await authStore.login(form.login, form.password);
  loading.value = false;

  if (res.success) {
    router.push('/spa/dashboard');
  } else {
    errorMessage.value = res.message;
  }
};
</script>
