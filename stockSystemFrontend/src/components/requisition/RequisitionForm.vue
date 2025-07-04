<template>
  <el-card>
    <h3 class="text-xl font-semibold mb-4">Create Requisition</h3>

    <el-form @submit.prevent="submit" :model="form" label-width="120px">
      <el-table :data="form.items" style="width: 100%">
        <el-table-column label="Item" width="260">
          <template #default="{ row }">
            <el-select v-model="row.id" filterable placeholder="Select Item" style="width: 100%">
              <el-option v-for="i in items.list" :key="i.id" :label="i.name" :value="i.id" />
            </el-select>
          </template>
        </el-table-column>

        <el-table-column label="Qty" width="120">
          <template #default="{ row }">
            <el-input-number v-model="row.qty" :min="1" :step="1" />
          </template>
        </el-table-column>

        <el-table-column width="100">
          <template #header>
            <el-button type="primary" @click="addRow">Add</el-button>
          </template>
          <template #default="{ $index }">
            <el-button icon="Delete" type="danger" size="small" @click="removeRow($index)"
              >Delete</el-button
            >
          </template>
        </el-table-column>
      </el-table>

      <el-form-item class="mt-4">
        <el-button type="success" native-type="submit">Submit</el-button>
      </el-form-item>
    </el-form>
  </el-card>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useItems } from '@/stores/useItems'
import { useRequisitions } from '@/stores/useRequisitions'
import { useNotifier } from '@/composables/useNotifier'

const items = useItems()
const req = useRequisitions()
const { showSuccess, showError } = useNotifier()

// Initial form
const form = ref({
  items: [{ id: null, qty: 1 }] as { id: number | null; qty: number }[],
})

// Methods
const addRow = () => {
  form.value.items.push({ id: null, qty: 1 })
}

const removeRow = (i: number) => {
  form.value.items.splice(i, 1)
}

// Submit Handler
const submit = async () => {
  try {
    const filtered = form.value.items.filter((i) => i.id && i.qty > 0)

    if (!filtered.length) {
      showError('Please select at least one item with valid quantity.')
      return
    }

    await req.create(filtered)
    showSuccess('Requisition created successfully!')
    form.value.items = [{ id: null, qty: 1 }]
  } catch (err) {
    showError('Failed to create requisition.')
    console.error(err)
  }
}

// Lifecycle
onMounted(items.fetch)
</script>
