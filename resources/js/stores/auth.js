import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user:  JSON.parse(localStorage.getItem('user'))  || null,
        token: localStorage.getItem('token') || null,
    }),

    getters: {
        isLoggedIn: (state) => !!state.token,
        isAdmin:    (state) => state.user?.role === 'admin',
        isEditor:   (state) => ['admin', 'editor'].includes(state.user?.role),
    },

    actions: {
        async login(email, password) {
            const res = await axios.post('/api/v1/auth/login', { email, password });
            this.token = res.data.token;
            this.user  = res.data.user;
            localStorage.setItem('token', this.token);
            localStorage.setItem('user',  JSON.stringify(this.user));
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        },

        logout() {
            this.token = null;
            this.user  = null;
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            delete axios.defaults.headers.common['Authorization'];
        },

        initAuth() {
            if (this.token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            }
        },
    },
});