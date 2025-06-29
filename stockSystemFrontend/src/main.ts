import { createApp } from 'vue'
import AppShell from '@/components/layout/AppShell.vue'
import router from '@/router'
import { createPinia } from 'pinia'
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'

createApp(AppShell).use(createPinia()).use(router).use(ElementPlus).mount('#app')
