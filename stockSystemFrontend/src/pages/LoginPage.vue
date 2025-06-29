<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <el-card class="w-96">
      <h3 class="text-xl mb-4 font-semibold">Login</h3>
      <el-form @submit.prevent="submit">
        <el-form-item>
          <el-input v-model="email" placeholder="Email" />
        </el-form-item>
        <el-form-item>
          <el-input v-model="password" type="password" placeholder="Password" />
        </el-form-item>
        <el-button type="primary" native-type="submit" class="w-full" :loading="loading"
          >Login</el-button
        >
        <p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
      </el-form>
    </el-card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuth } from '@/stores/useAuth'
import { useRouter } from 'vue-router'

const email = ref('admin@example.com')
const password = ref('password')
const loading = ref(false)
const error = ref('')
const auth = useAuth()
const router = useRouter()

const submit = async () => {
  try {
    loading.value = true
    await auth.login(email.value, password.value)
    if (auth.isAdmin) router.push('/admin/requisitions')
    else if (auth.isEmployee) router.push('/employee/requisitions')
    else if (auth.isExec) router.push('/store/stock')
  } catch (e) {
    error.value = 'Invalid credentials'
  } finally {
    loading.value = false
  }
}
</script>
