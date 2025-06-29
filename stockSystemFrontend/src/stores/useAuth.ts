import { defineStore } from 'pinia'
import api from '@/api/axios'

interface User {
  id: number
  name: string
  role: 'admin' | 'employee' | 'store_executive'
}

export const useAuth = defineStore('auth', {
  state: () => ({ token: localStorage.getItem('token'), user: null as null | User }),
  getters: {
    isLoggedIn: (s) => !!s.token,
    isAdmin: (s) => s.user?.role === 'admin',
    isEmployee: (s) => s.user?.role === 'employee',
    isExec: (s) => s.user?.role === 'store_executive',
  },
  actions: {
    async login(email: string, password: string) {
      const { data } = await api.post('/login', { email, password })
      this.token = data.token
      localStorage.setItem('token', data.token)
      await this.me()
    },
    async me() {
      this.user = (await api.get('/me')).data
    },
    async logout() {
      await api.post('/logout')
      this.token = null
      this.user = null
      localStorage.removeItem('token')
    },
  },
})
