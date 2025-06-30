<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Issue Approved Requisitions (FIFO)</h2>

    <el-select
      v-model="selectedId"
      placeholder="Select requisition"
      filterable
      class="w-96"
      @change="onSelect"
    >
      <el-option
        v-for="r in approved"
        :key="r.id"
        :label="`#${r.id} — ${r.creator.name}`"
        :value="r.id"
      />
    </el-select>

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
import { ElMessage } from 'element-plus'
import api from '@/api/axios'
import { useNotifier } from '@/composables/useNotifier'
const selectedId = ref<number | null>(null)

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

const { showSuccess, showError } = useNotifier()
const approved = ref<Req[]>([])
const selected = ref<Req | null>(null)
const costMap = reactive<Record<number, number>>({})
const grandTotal = computed(() => Object.values(costMap).reduce((a, b) => a + b, 0))
const saving = ref(false)

const onSelect = () => {
  selected.value = approved.value.find((r) => r.id === selectedId.value) || null
  previewCost()
}

const fetchApproved = async () => {
  try {
    approved.value = (await api.get('/requisitions/approved')).data
  } catch (err) {
    showError('Failed to load approved requisitions')
  }
}

const previewCost = async () => {
  Object.keys(costMap).forEach((k) => delete costMap[+k])
  if (!selected.value) return
  try {
    const res = await api.post('/stock/fifo-preview', { requisition_id: selected.value.id })
    Object.assign(costMap, res.data.item_totals)
  } catch (err) {
    showError('Failed to fetch FIFO preview')
  }
}

const issue = async () => {
  if (!selected.value) return
  saving.value = true
  try {
    await api.post(`/stock/issue/${selected.value.id}`)
    showSuccess('Items issued successfully!')
    await fetchApproved()
    selected.value = null
    selectedId.value = null
  } catch (err) {
    showError('Failed to issue requisition')
  } finally {
    saving.value = false
  }
}

onMounted(fetchApproved)
</script>
