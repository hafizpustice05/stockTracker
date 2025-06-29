import { defineStore } from 'pinia'
import api from '@/api/axios'

export const useItems = defineStore('items', {
  state: () => ({ list: [] as any[] }),
  actions: {
    async fetch() {
      this.list = (await api.get('/items')).data
      console.log('Items fetched:', this.list)
    },
  },
})
