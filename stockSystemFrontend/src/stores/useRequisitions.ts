import { defineStore } from 'pinia'
import api from '@/api/axios'

export const useRequisitions = defineStore('req', {
  state: () => ({ my: [], pending: [], approved: [] }) as any,
  actions: {
    async create(items: { id: number; qty: number }[]) {
      await api.post('/requisitions', { items })
      await this.fetchMy()
    },
    async fetchMy() {
      this.my = (await api.get('/requisitions/my')).data
    },
    async fetchPending() {
      this.pending = (await api.get('/requisitions/pending')).data
    },
    async approve(id: number) {
      await api.patch(`/requisitions/${id}/approve`)
      await this.fetchPending()
    },
    async reject(id: number, reason: string) {
      await api.patch(`/requisitions/${id}/reject`, { reason })
      await this.fetchPending()
    },
  },
})
