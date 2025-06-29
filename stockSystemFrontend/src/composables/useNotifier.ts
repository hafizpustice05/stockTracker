// src/composables/useNotifier.ts
import { ElNotification } from 'element-plus'

export function useNotifier() {
  const showSuccess = (message: string, title = 'Success') => {
    ElNotification({ title, message, type: 'success' })
  }

  const showError = (message: string, title = 'Error') => {
    ElNotification({ title, message, type: 'error' })
  }

  const showWarning = (message: string, title = 'Warning') => {
    ElNotification({ title, message, type: 'warning' })
  }

  const showInfo = (message: string, title = 'Info') => {
    ElNotification({ title, message, type: 'info' })
  }

  return { showSuccess, showError, showWarning, showInfo }
}
