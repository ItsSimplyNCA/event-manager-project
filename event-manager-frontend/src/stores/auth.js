import { defineStore } from 'pinia'
import api from '../lib/api'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        initialized: false,
    }),

    getters: {
        isAuthenticated: (state) => !!state.user,
        isAgent: (state) => state.user?.role === 'agent',
    },

    actions: {
        async initialize() {
            if (this.initialized) return

            try {
                const { data } = await api.get('/api/me')
                this.user = data.user
            } catch {
                this.user = null
            } finally {
                this.initialized = true
            }
        },

        async login(payload) {
            await api.get('/sanctum/csrf-cookie')
            await api.post('/login', payload)

            const { data } = await api.get('/api/me')
            this.user = data.user
            this.initialized = true

            return data.user
        },

        async logout() {
            await api.post('/logout')
            this.user = null
            this.initialized = true
        },
    },
})