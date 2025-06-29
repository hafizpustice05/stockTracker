import { defineStore } from 'pinia'
import api from '@/api/axios'

export const useStock = defineStore('stock', {
  state: () => ({ list: [] as any[] }),
  actions: {
    async fetch() {
      this.list = (await api.get('/stock')).data
    },
  },
})
