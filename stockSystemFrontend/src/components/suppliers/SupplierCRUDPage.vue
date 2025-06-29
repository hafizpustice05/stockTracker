<template>
  <div class="space-y-6">
    <h2 class="text-2xl font-semibold">Manage Suppliers</h2>

    <!-- ────── Form ────── -->
    <el-card shadow="never">
      <el-form
        ref="supplierForm"
        :model="form"
        :rules="rules"
        class="md:flex md:flex-wrap gap-4"
        label-width="100px"
        @submit.prevent="submit"
      >
        <el-form-item label="Name" prop="name" class="flex-1 min-w-[250px]">
          <el-input v-model="form.name" autocomplete="off" />
        </el-form-item>

        <el-form-item label="Phone" prop="phone" class="flex-1 min-w-[200px]">
          <el-input v-model="form.phone" autocomplete="off" />
        </el-form-item>

        <el-form-item label="Email" prop="email" class="flex-1 min-w-[250px]">
          <el-input v-model="form.email" autocomplete="off" />
        </el-form-item>

        <el-form-item label="Address" prop="address" class="flex-1 min-w-[300px]">
          <el-input v-model="form.address" autocomplete="off" />
        </el-form-item>

        <el-form-item label="Status" prop="status">
          <el-select v-model="form.status" class="w-28">
            <el-option label="Active" value="active" />
            <el-option label="Inactive" value="inactive" />
          </el-select>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" native-type="submit" :loading="saving">
            {{ form.id ? 'Update' : 'Add' }} Supplier
          </el-button>
          <el-button @click="resetForm" :disabled="saving">Reset</el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <!-- ────── Data Table ────── -->
    <el-table :data="suppliers.list" stripe>
      <el-table-column prop="name" label="Name" />
      <el-table-column prop="phone" label="Phone" />
      <el-table-column prop="email" label="Email" />
      <el-table-column prop="address" label="Address" />
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
import { useSuppliers } from '@/stores/useSuppliers'
import api from '@/api/axios'
import { useNotifier } from '@/composables/useNotifier'

interface SupplierForm {
  id: number | null
  name: string
  phone: string
  email: string
  address: string
  status: 'active' | 'inactive'
}

const suppliers = useSuppliers()
const { showSuccess, showError } = useNotifier()

// reactive form + validation rules
const defaultForm: SupplierForm = {
  id: null,
  name: '',
  phone: '',
  email: '',
  address: '',
  status: 'active',
}

const form = ref<SupplierForm>({ ...defaultForm })
const supplierForm = ref<InstanceType<typeof ElForm>>()
const saving = ref(false)
const deletingId = ref<number | null>(null)

const rules = {
  name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
  email: [
    { required: true, message: 'Email is required', trigger: 'blur' },
    { type: 'email', message: 'Invalid email', trigger: 'blur' },
  ],
}

function resetForm() {
  form.value = { ...defaultForm }
  supplierForm.value?.clearValidate()
}

async function submit() {
  await supplierForm.value?.validate(async (valid: boolean) => {
    if (!valid) return

    saving.value = true
    try {
      if (form.value.id) {
        await api.put(`/suppliers/${form.value.id}`, form.value)
        showSuccess('Supplier updated')
      } else {
        await api.post('/suppliers', form.value)
        showSuccess('Supplier created')
      }
      await suppliers.fetch()
      resetForm()
    } catch (err) {
      console.error(err)
      showError('Failed to save supplier')
    } finally {
      saving.value = false
    }
  })
}

function edit(row: SupplierForm) {
  form.value = { ...row }
  supplierForm.value?.clearValidate()
}

async function remove(id: number) {
  if (!confirm('Are you sure?')) return
  deletingId.value = id
  try {
    await api.delete(`/suppliers/${id}`)
    await suppliers.fetch()
    showSuccess('Supplier deleted')
  } catch (err) {
    console.error(err)
    showError('Failed to delete supplier')
  } finally {
    deletingId.value = null
  }
}

// initial load
onMounted(suppliers.fetch)
</script>

<style scoped>
/* optional minor spacing tweak */
.el-card {
  padding: 1rem;
}
</style>
