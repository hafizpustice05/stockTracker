<!-- <template>
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
</script> -->

<template>
  <div class="space-y-6">
    <h2 class="text-2xl font-semibold">Manage Items</h2>

    <!-- ────── Item Form ────── -->
    <el-card shadow="never">
      <el-form
        ref="itemForm"
        :model="form"
        :rules="rules"
        label-width="100px"
        class="md:flex md:flex-wrap gap-4"
        @submit.prevent="submit"
      >
        <el-form-item label="Name" prop="name" class="flex-1 min-w-[250px]">
          <el-input v-model="form.name" autocomplete="off" />
        </el-form-item>

        <el-form-item label="Description" prop="description" class="flex-1 min-w-[300px]">
          <el-input v-model="form.description" type="textarea" :rows="2" />
        </el-form-item>

        <el-form-item label="Status" prop="status">
          <el-select v-model="form.status" class="w-28">
            <el-option label="Active" value="active" />
            <el-option label="Inactive" value="inactive" />
          </el-select>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" native-type="submit" :loading="saving">
            {{ form.id ? 'Update' : 'Add' }} Item
          </el-button>
          <el-button @click="resetForm" :disabled="saving">Reset</el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <!-- ────── Item Table ────── -->
    <el-table :data="items.list" stripe>
      <el-table-column prop="name" label="Name" />
      <el-table-column prop="description" label="Description" />
      <el-table-column label="Status">
        <template #default="{ row }">
          <el-tag :type="row.status === 'active' ? 'success' : 'info'" effect="plain">
            {{ row.status }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Actions" width="180">
        <template #default="{ row }">
          <el-button size="small" @click="edit(row)">Edit</el-button>
          <el-button
            size="small"
            type="danger"
            @click="remove(row.id)"
            :loading="deletingId === row.id"
          >
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { ElForm } from 'element-plus'
import { useItems } from '@/stores/useItems'
import api from '@/api/axios'
import { useNotifier } from '@/composables/useNotifier'

interface ItemForm {
  id: number | null
  name: string
  description: string
  status: 'active' | 'inactive'
}

const items = useItems()
const { showSuccess, showError } = useNotifier()

const defaultForm: ItemForm = {
  id: null,
  name: '',
  description: '',
  status: 'active',
}

const form = ref<ItemForm>({ ...defaultForm })
const itemForm = ref<InstanceType<typeof ElForm>>()
const saving = ref(false)
const deletingId = ref<number | null>(null)

const rules = {
  name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
  description: [{ required: true, message: 'Description is required', trigger: 'blur' }],
}

function resetForm() {
  form.value = { ...defaultForm }
  itemForm.value?.clearValidate()
}

async function submit() {
  await itemForm.value?.validate(async (valid: boolean) => {
    if (!valid) return
    saving.value = true
    try {
      if (form.value.id) {
        await api.put(`/items/${form.value.id}`, form.value)
        showSuccess('Item updated')
      } else {
        await api.post('/items', form.value)
        showSuccess('Item created')
      }
      await items.fetch()
      resetForm()
    } catch (err) {
      console.error(err)
      showError('Failed to save item')
    } finally {
      saving.value = false
    }
  })
}

function edit(row: ItemForm) {
  form.value = { ...row }
  itemForm.value?.clearValidate()
}

async function remove(id: number) {
  if (!confirm('Are you sure?')) return
  deletingId.value = id
  try {
    await api.delete(`/items/${id}`)
    await items.fetch()
    showSuccess('Item deleted')
  } catch (err) {
    console.error(err)
    showError('Failed to delete item')
  } finally {
    deletingId.value = null
  }
}

onMounted(items.fetch)
</script>

<style scoped>
.el-card {
  padding: 1rem;
}
</style>
