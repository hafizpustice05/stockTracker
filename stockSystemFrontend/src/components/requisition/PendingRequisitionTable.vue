<template>
  <el-card
    ><h3>Pending Requisitions</h3>
    <el-table :data="store.pending" style="width: 100%">
      <el-table-column prop="id" label="#" width="60" />
      <el-table-column prop="creator.name" label="Employee" />
      <el-table-column label="Items">
        <template #default="{ row }">
          <ul class="list-disc ml-4">
            <li v-for="i in row.items" :key="i.id">{{ i.name }} x {{ i.pivot.qty }}</li>
          </ul>
        </template>
      </el-table-column>
      <el-table-column width="180">
        <template #default="{ row }">
          <ConfirmButton
            title="Are you approve this requisitions?"
            type="success"
            @confirm="approve(row.id)"
            >Approve</ConfirmButton
          >
          <ConfirmButton
            title="Are you reject this requisitions?"
            type="danger"
            @confirm="reject(row.id)"
            >Reject</ConfirmButton
          >
        </template>
      </el-table-column>
    </el-table>
  </el-card>
</template>
<script setup>
import { onMounted } from 'vue'
import { useRequisitions } from '@/stores/useRequisitions'
import ConfirmButton from '@/components/shared/ConfirmButton.vue'

const store = useRequisitions()
const approve = (id) => store.approve(id)
const reject = (id) => store.reject(id, 'Not available')
onMounted(store.fetchPending)
</script>
