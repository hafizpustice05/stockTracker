<template>
  <el-card>
    <h3>Pending Requisitions</h3>
    <el-table
      :data="store.pending"
      style="width: 100%"
      v-loading="loading"
      element-loading-text="Loading..."
      stripe
    >
      <el-table-column prop="id" label="#" width="60" />
      <el-table-column prop="creator.name" label="Employee" />
      <el-table-column label="Items">
        <template #default="{ row }">
          <ul class="list-disc ml-4">
            <li v-for="i in row.items" :key="i.id">{{ i.name }} x {{ i.pivot.qty }}</li>
          </ul>
        </template>
      </el-table-column>
      <el-table-column width="180" label="Actions">
        <template #default="{ row }">
          <ConfirmButton
            title="Are you approve this requisition?"
            type="success"
            :disabled="actionLoading === row.id"
            @confirm="() => approve(row.id)"
          >
            Approve
          </ConfirmButton>

          <ConfirmButton
            title="Are you reject this requisition?"
            type="danger"
            :disabled="actionLoading === row.id"
            @confirm="() => reject(row.id)"
          >
            Reject
          </ConfirmButton>
        </template>
      </el-table-column>
    </el-table>
  </el-card>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRequisitions } from '@/stores/useRequisitions'
import { useNotifier } from '@/composables/useNotifier'
import ConfirmButton from '@/components/shared/ConfirmButton.vue'

const store = useRequisitions()
const { showSuccess, showError } = useNotifier()
const loading = ref(false)
const actionLoading = ref(null)

const approve = async (id) => {
  actionLoading.value = id
  try {
    await store.approve(id)
    showSuccess('Requisition approved successfully')
    await store.fetchPending()
  } catch (error) {
    showError('Failed to approve requisition')
  } finally {
    actionLoading.value = null
  }
}

const reject = async (id) => {
  actionLoading.value = id
  try {
    await store.reject(id, 'Rejected by admin')
    showSuccess('Requisition rejected successfully')
    await store.fetchPending()
  } catch (error) {
    showError('Failed to reject requisition')
  } finally {
    actionLoading.value = null
  }
}

onMounted(async () => {
  loading.value = true
  try {
    await store.fetchPending()
  } finally {
    loading.value = false
  }
})
</script>
