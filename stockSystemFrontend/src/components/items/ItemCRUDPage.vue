<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Manage Items</h2>

    <el-form :model="form" inline class="mb-4" @submit.prevent="submit">
      <el-form-item label="Name">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="Description">
        <el-input v-model="form.description" />
      </el-form-item>
      <el-form-item label="Status">
        <el-select v-model="form.status" placeholder="Select">
          <el-option label="Active" value="active" />
          <el-option label="Inactive" value="inactive" />
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" native-type="submit">
          {{ form.id ? 'Update' : 'Add' }} Item
        </el-button>
        <el-button @click="resetForm">Reset</el-button>
      </el-form-item>
    </el-form>

    <el-table :data="items.list" stripe style="width: 100%">
      <el-table-column prop="name" label="Name" />
      <el-table-column prop="description" label="Description" />
      <el-table-column prop="status" label="Status" />
      <el-table-column label="Actions">
        <template #default="{ row }">
          <el-button size="small" @click="edit(row)">Edit</el-button>
          <el-button size="small" type="danger" @click="remove(row.id)">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useItems } from '@/stores/useItems'
import api from '@/api/axios'

const items = useItems()
const form = ref({ name: '', description: '', status: 'Active', id: null })

const resetForm = () => {
  form.value = { name: '', description: '', status: 'Active', id: null }
}

const submit = async () => {
  if (form.value.id) {
    await api.put(`/items/${form.value.id}`, form.value)
  } else {
    await api.post('/items', form.value)
  }
  await items.fetch()
  resetForm()
}

const edit = (item) => {
  form.value = { ...item }
}

const remove = async (id) => {
  if (confirm('Are you sure?')) {
    await api.delete(`/items/${id}`)
    await items.fetch()
  }
}

onMounted(items.fetch)
</script>
