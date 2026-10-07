import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('flat_kharch_token') || null,
        user: JSON.parse(localStorage.getItem('flat_kharch_user') || 'null'),
        loading: false,
        error: null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token && !!state.user,
        isAdmin: (state) => state.user?.is_admin || state.user?.role === 1,
        userName: (state) => state.user?.name || 'Flatmate',
        userAvatar: (state) => state.user?.avatar_url || '',
    },

    actions: {
        async login(loginInput, password) {
            this.loading = true;
            this.error = null;
            try {
                const response = await apiClient.post('/auth/login', {
                    login: loginInput,
                    password: password,
                });

                if (response.data.success) {
                    this.token = response.data.data.token;
                    this.user = response.data.data.user;

                    localStorage.setItem('flat_kharch_token', this.token);
                    localStorage.setItem('flat_kharch_user', JSON.stringify(this.user));

                    return { success: true };
                } else {
                    this.error = response.data.message || 'Login failed';
                    return { success: false, message: this.error };
                }
            } catch (err) {
                const msg = err.response?.data?.message || 'Invalid credentials or server error';
                this.error = msg;
                return { success: false, message: msg };
            } finally {
                this.loading = false;
            }
        },

        async fetchUser() {
            if (!this.token) return;
            try {
                const response = await apiClient.get('/auth/user');
                if (response.data.success) {
                    this.user = response.data.data;
                    localStorage.setItem('flat_kharch_user', JSON.stringify(this.user));
                }
            } catch (err) {
                this.logout();
            }
        },

        async logout() {
            try {
                if (this.token) {
                    await apiClient.post('/auth/logout');
                }
            } catch (err) {
                // Ignore errors on logout
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('flat_kharch_token');
                localStorage.removeItem('flat_kharch_user');
            }
        },
    },
});
