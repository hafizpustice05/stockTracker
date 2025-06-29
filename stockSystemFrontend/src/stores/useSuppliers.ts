import { defineStore } from 'pinia'
import api from '@/api/axios'
export const useSuppliers = defineStore('suppliers', {
  state: () => ({ list: [] as any[] }),
  actions: {
    async fetch() {
      this.list = (await api.get('/suppliers')).data
      console.log('Suppliers fetched:', this.list)
    },
  },
})
