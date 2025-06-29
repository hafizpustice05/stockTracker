<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">My Requisitions</h2>

    <el-table :data="myRequisitions" style="width: 100%" v-loading="loading" stripe>
      <el-table-column prop="id" label="#ID" width="80" />
      <el-table-column prop="created_at" label="Date" />
      <el-table-column label="Items">
        <template #default="{ row }">
          <ul>
            <li v-for="item in row.items" :key="item.id">
              {{ item.name }} — Qty: {{ item.pivot.qty }}
            </li>
          </ul>
        </template>
      </el-table-column>
      <el-table-column label="Status">
        <template #default="{ row }">
          <el-tag
            :type="
              row.status === 'approved' ? 'success' : row.status === 'rejected' ? 'danger' : 'info'
            "
            disable-transitions
          >
            {{ row.status }}
          </el-tag>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/axios'

const myRequisitions = ref([])
const loading = ref(false)

const fetchRequisitions = async () => {
  loading.value = true
  try {
    const res = await api.get('/requisitions/my')
    myRequisitions.value = res.data
    console.log('Requisitions loaded:', myRequisitions.value)
  } catch (err) {
    console.error('Failed to load requisitions', err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchRequisitions)
</script>
