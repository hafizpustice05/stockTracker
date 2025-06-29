<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Issue Approved Requisitions (FIFO)</h2>

    <!-- ▼ Step 1: choose an approved requisition -->
    <el-select
      v-model="selected"
      placeholder="Select requisition"
      filterable
      class="w-96"
      @change="previewCost"
    >
      <el-option
        v-for="r in approved"
        :key="r.id"
        :label="`#${r.id} — ${r.creator.name}`"
        :value="r"
      />
    </el-select>

    <!-- ▼ Step 2: show breakdown + estimated FIFO cost -->
    <el-table v-if="selected" :data="selected.items" class="mt-6">
      <el-table-column prop="name" label="Item" />
      <el-table-column prop="pivot.qty" label="Qty" width="100" />
      <el-table-column label="FIFO Cost" width="160">
        <template #default="{ row }">
          {{ costMap[row.id] || 0 }}
        </template>
      </el-table-column>
    </el-table>

    <div v-if="selected" class="mt-4 flex items-center gap-4">
      <p class="font-medium">Estimated Total: {{ grandTotal }}</p>
      <el-button type="primary" :disabled="saving" @click="issue">Issue Now</el-button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import api from '@/api/axios'

interface ReqItem {
  id: number
  name: string
  pivot: { qty: number }
}
interface Req {
  id: number
  creator: { name: string }
  items: ReqItem[]
}

const approved = ref<Req[]>([])
const selected = ref<Req | null>(null)
const costMap = reactive<Record<number, number>>({})
const grandTotal = computed(() => Object.values(costMap).reduce((a, b) => a + b, 0))
const saving = ref(false)

// Fetch approved requisitions for store exec
const fetchApproved = async () => {
  approved.value = (await api.get('/requisitions/approved')).data
}

// Optional preview of FIFO cost (server returns price per item)
const previewCost = async () => {
  Object.keys(costMap).forEach((k) => delete costMap[+k])
  if (!selected.value) return
  const res = await api.post('/stock/fifo-preview', { requisition_id: selected.value.id })
  console.log('FIFO preview response:', res.data)
  Object.assign(costMap, res.data.item_totals) // e.g. { '3': 460, '5': 1200 }
}

const issue = async () => {
  if (!selected.value) return
  saving.value = true
  try {
    await api.post(`/stock/issue/${selected.value.id}`)
    await fetchApproved()
    selected.value = null
  } finally {
    saving.value = false
  }
}

onMounted(fetchApproved)
</script>
