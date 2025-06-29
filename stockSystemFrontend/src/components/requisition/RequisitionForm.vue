<template>
  <el-card
    ><h3>Create Requisition</h3>
    <el-form @submit.prevent="submit" :model="form">
      <el-table :data="form.items" style="width: 100%">
        <el-table-column label="Item" width="260">
          <template #default="{ row }">
            <el-select v-model="row.id" filterable placeholder="Select" style="width: 100%">
              <el-option v-for="i in items.list" :key="i.id" :label="i.name" :value="i.id" />
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="Qty" width="120">
          <template #default="{ row }">
            <el-input-number v-model="row.qty" :min="1" />
          </template>
        </el-table-column>
        <el-table-column>
          <template #header>
            <el-button type="primary" @click="addRow">Add</el-button>
          </template>
          <template #default="{ $index }">
            <el-button icon="Delete" type="danger" @click="removeRow($index)">Delete</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-button class="mt-4" type="success" native-type="submit">Submit</el-button>
    </el-form>
  </el-card>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useItems } from '@/stores/useItems'
import { useRequisitions } from '@/stores/useRequisitions'

const items = useItems()
const req = useRequisitions()
const form = ref({ items: [{ id: null, qty: 1 }] as { id: number | null; qty: number }[] })
const addRow = () => form.value.items.push({ id: null, qty: 1 })
const removeRow = (i: number) => form.value.items.splice(i, 1)
const submit = async () => {
  await req.create(form.value.items.filter((i) => i.id))
  form.value.items = [{ id: null, qty: 1 }]
}
onMounted(items.fetch)
</script>
