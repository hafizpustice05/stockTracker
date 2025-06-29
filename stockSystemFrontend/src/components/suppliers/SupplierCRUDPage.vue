<!-- <template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Manage Suppliers</h2>

    <el-form :model="form" inline class="mb-4" @submit.prevent="submit">
      <el-form-item label="Name">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="Phone">
        <el-input v-model="form.phone" />
      </el-form-item>
      <el-form-item label="Email">
        <el-input v-model="form.email" />
      </el-form-item>
      <el-form-item label="Address">
        <el-input v-model="form.address" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" native-type="submit">
          {{ form.id ? 'Update' : 'Add' }} Supplier
        </el-button>
        <el-button @click="resetForm">Reset</el-button>
      </el-form-item>
    </el-form>

    <el-table :data="suppliers.list" stripe style="width: 100%">
      <el-table-column prop="name" label="Name" />
      <el-table-column prop="phone" label="Phone" />
      <el-table-column prop="email" label="Email" />
      <el-table-column prop="address" label="Address" />
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
import { useSuppliers } from '@/stores/useSuppliers'
import api from '@/api/axios'

const suppliers = useSuppliers()
const form = ref({ name: '', phone: '', email: '', address: '', status: 'active', id: null })

const resetForm = () => {
  form.value = { name: '', phone: '', email: '', address: '', status: 'active', id: null }
}

const submit = async () => {
  if (form.value.id) {
    await api.put(`/suppliers/${form.value.id}`, form.value)
  } else {
    await api.post('/suppliers', form.value)
  }
  await suppliers.fetch()
  resetForm()
}

const edit = (supplier) => {
  form.value = { ...supplier }
}

const remove = async (id) => {
  if (confirm('Are you sure?')) {
    await api.delete(`/suppliers/${id}`)
    await suppliers.fetch()
  }
}

onMounted(suppliers.fetch)
</script> -->

<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Manage Suppliers</h2>

    <el-form :model="form" inline class="mb-4" @submit.prevent="submit">
      <el-form-item label="Name">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="Phone">
        <el-input v-model="form.phone" />
      </el-form-item>
      <el-form-item label="Email">
        <el-input v-model="form.email" />
      </el-form-item>
      <el-form-item label="Address">
        <el-input v-model="form.address" />
      </el-form-item>
      <el-form-item label="Status">
        <el-select v-model="form.status" class="w-32">
          <el-option label="Active" value="active" />
          <el-option label="Inactive" value="inactive" />
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" native-type="submit">
          {{ form.id ? 'Update' : 'Add' }} Supplier
        </el-button>
        <el-button @click="resetForm">Reset</el-button>
      </el-form-item>
    </el-form>

    <el-table :data="suppliers.list" stripe style="width: 100%">
      <el-table-column prop="name" label="Name" />
      <el-table-column prop="phone" label="Phone" />
      <el-table-column prop="email" label="Email" />
      <el-table-column prop="address" label="Address" />
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
import { useSuppliers } from '@/stores/useSuppliers'
import api from '@/api/axios'

const suppliers = useSuppliers()
const form = ref({
  name: '',
  phone: '',
  email: '',
  address: '',
  status: 'active',
  id: null,
})

const resetForm = () => {
  form.value = { name: '', phone: '', email: '', address: '', status: 'active', id: null }
}

const submit = async () => {
  if (form.value.id) {
    await api.put(`/suppliers/${form.value.id}`, form.value)
  } else {
    await api.post('/suppliers', form.value)
  }
  await suppliers.fetch()
  resetForm()
}

const edit = (supplier) => {
  form.value = { ...supplier }
}

const remove = async (id) => {
  if (confirm('Are you sure?')) {
    await api.delete(`/suppliers/${id}`)
    await suppliers.fetch()
  }
}

onMounted(suppliers.fetch)
</script>
