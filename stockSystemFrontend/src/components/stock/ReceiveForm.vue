<!-- <template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Receive Items</h2>

    <el-form :model="form" label-width="120px" @submit.prevent="submit" class="max-w-xl">
      <el-form-item label="Supplier">
        <el-select v-model="form.supplier_id" placeholder="Select" filterable>
          <el-option v-for="s in suppliers.list" :key="s.id" :label="s.name" :value="s.id" />
        </el-select>
      </el-form-item>

      <el-form-item label="Item">
        <el-select v-model="form.item_id" placeholder="Select" filterable>
          <el-option v-for="i in items.list" :key="i.id" :label="i.name" :value="i.id" />
        </el-select>
      </el-form-item>

      <el-form-item label="Quantity">
        <el-input-number v-model="form.qty" :min="1" />
      </el-form-item>

      <el-form-item label="Unit Price">
        <el-input-number v-model="form.unit_price" :min="1" />
      </el-form-item>

      <el-form-item>
        <el-button type="primary" native-type="submit" :loading="saving">Receive</el-button>
        <el-button @click="resetForm">Reset</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useItems } from '@/stores/useItems'
import { useSuppliers } from '@/stores/useSuppliers'
import { useStock } from '@/stores/useStock'
import api from '@/api/axios'

const items = useItems()
const suppliers = useSuppliers()
const stock = useStock()

const form = ref({ supplier_id: null, item_id: null, qty: 1, unit_price: 0 })
const saving = ref(false)

const resetForm = () => {
  form.value = { supplier_id: null, item_id: null, qty: 1, unit_price: 0 }
}

const submit = async () => {
  if (!form.value.supplier_id || !form.value.item_id)
    return alert('Please select supplier and item')
  saving.value = true
  try {
    await api.post('/stock/receive', form.value)
    await stock.fetch()
    resetForm()
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  items.fetch()
  suppliers.fetch()
})
</script> -->

<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Receive Items</h2>

    <el-form :model="form" label-width="120px" @submit.prevent="submit" class="max-w-xl">
      <el-form-item label="Supplier">
        <el-select v-model="form.supplier_id" placeholder="Select" filterable>
          <el-option v-for="s in suppliers.list" :key="s.id" :label="s.name" :value="s.id" />
        </el-select>
      </el-form-item>

      <el-form-item label="Item">
        <el-select v-model="form.item_id" placeholder="Select" filterable>
          <el-option v-for="i in items.list" :key="i.id" :label="i.name" :value="i.id" />
        </el-select>
      </el-form-item>

      <el-form-item label="Quantity">
        <el-input-number v-model="form.qty" :min="1" />
      </el-form-item>

      <el-form-item label="Unit Price">
        <el-input-number v-model="form.unit_price" :min="1" />
      </el-form-item>

      <el-form-item>
        <el-button type="primary" native-type="submit" :loading="saving">Receive</el-button>
        <el-button @click="resetForm">Reset</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useItems } from '@/stores/useItems'
import { useSuppliers } from '@/stores/useSuppliers'
import { useStock } from '@/stores/useStock'
import api from '@/api/axios'

const items = useItems()
const suppliers = useSuppliers()
const stock = useStock()

const form = ref({ supplier_id: null, item_id: null, qty: 1, unit_price: 0 })
const saving = ref(false)

const resetForm = () => {
  form.value = { supplier_id: null, item_id: null, qty: 1, unit_price: 0 }
}

const submit = async () => {
  if (!form.value.supplier_id || !form.value.item_id)
    return alert('Please select supplier and item')
  saving.value = true
  try {
    await api.post('/stock/receive', form.value)
    await stock.fetch()
    resetForm()
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  items.fetch()
  suppliers.fetch()
  stock.fetch()
})
</script>
