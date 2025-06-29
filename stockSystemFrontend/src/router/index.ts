import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '@/pages/LoginPage.vue'
import { useAuth } from '@/stores/useAuth'

const routes = [
  { path: '/login', component: LoginPage },
  { path: '/', redirect: '/dashboard' },
  {
    path: '/dashboard',
    component: () => import('@/pages/dashboard/EmpHome.vue'),
    meta: { requiresAuth: true },
  },
  // Role‑specific bundles (lazy)
  {
    path: '/admin/items',
    component: () => import('@/components/items/ItemCRUDPage.vue'),
    meta: { requiresAuth: true, roles: ['admin'] },
  },
  {
    path: '/admin/suppliers',
    component: () => import('@/components/suppliers/SupplierCRUDPage.vue'),
    meta: { requiresAuth: true, roles: ['admin'] },
  },
  {
    path: '/admin/requisitions',
    component: () => import('@/components/requisition/PendingRequisitionTable.vue'),
    meta: { requiresAuth: true, roles: ['admin'] },
  },

  {
    path: '/employee/requisitions/new',
    component: () => import('@/components/requisition/RequisitionForm.vue'),
    meta: { requiresAuth: true, roles: ['employee'] },
  },
  {
    path: '/employee/requisitions',
    component: () => import('@/components/requisition/MyRequisitionTable.vue'),
    meta: { requiresAuth: true, roles: ['employee'] },
  },

  {
    path: '/store/receive',
    component: () => import('@/components/stock/ReceiveForm.vue'),
    meta: { requiresAuth: true, roles: ['store_executive'] },
  },
  {
    path: '/store/issue',
    component: () => import('@/components/stock/IssueWizard.vue'),
    meta: { requiresAuth: true, roles: ['store_executive'] },
  },
  {
    path: '/store/stock',
    component: () => import('@/components/stock/StockTable.vue'),
    meta: { requiresAuth: true, roles: ['store_executive'] },
  },
]

const router = createRouter({ history: createWebHistory(), routes })

let isInitialAuthChecked = false

router.beforeEach(async (to, from, next) => {
  const auth = useAuth()

  // Wait for user to be fetched if token exists but user not loaded
  if (auth.token && !auth.user && !isInitialAuthChecked) {
    try {
      await auth.me()
    } catch {
      auth.logout()
    }
    isInitialAuthChecked = true
  }

  if (to.meta.requiresAuth && !auth.isLoggedIn) return next('/login')
  if (to.meta.roles && !to.meta.roles.includes(auth.user?.role)) return next('/dashboard')

  next()
})

export default router
