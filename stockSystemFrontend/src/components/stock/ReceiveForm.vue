<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Receive Items</h2>

    <el-form
      :model="form"
      label-width="120px"
      class="max-w-xl"
      @submit.prevent="submit"
      ref="receiveForm"
    >
      <el-form-item
        label="Supplier"
        prop="supplier_id"
        :rules="[{ required: true, message: 'Supplier is required' }]"
      >
        <el-select v-model="form.supplier_id" placeholder="Select Supplier" filterable>
          <el-option v-for="s in suppliers.list" :key="s.id" :label="s.name" :value="s.id" />
        </el-select>
      </el-form-item>

      <el-form-item
        label="Item"
        prop="item_id"
        :rules="[{ required: true, message: 'Item is required' }]"
      >
        <el-select v-model="form.item_id" placeholder="Select Item" filterable>
          <el-option v-for="i in items.list" :key="i.id" :label="i.name" :value="i.id" />
        </el-select>
      </el-form-item>

      <el-form-item
        label="Quantity"
        prop="qty"
        :rules="[{ required: true, message: 'Quantity is required' }]"
      >
        <el-input-number v-model="form.qty" :min="1" />
      </el-form-item>

      <el-form-item
        label="Unit Price"
        prop="unit_price"
        :rules="[{ required: true, message: 'Price is required' }]"
      >
        <el-input-number v-model="form.unit_price" :min="1" />
      </el-form-item>

      <el-form-item>
        <el-button type="primary" native-type="submit" :loading="saving">Receive</el-button>
        <el-button @click="resetForm" :disabled="saving">Reset</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useItems } from '@/stores/useItems'
import { useSuppliers } from '@/stores/useSuppliers'
import { useStock } from '@/stores/useStock'
import { useNotifier } from '@/composables/useNotifier'
import api from '@/api/axios'
import { ElForm } from 'element-plus'

const { showSuccess, showError } = useNotifier()
const items = useItems()
const suppliers = useSuppliers()
const stock = useStock()

const receiveForm = ref<InstanceType<typeof ElForm>>()

const form = ref({
  supplier_id: null,
  item_id: null,
  qty: 1,
  unit_price: 0,
})

const saving = ref(false)

function resetForm() {
  form.value = {
    supplier_id: null,
    item_id: null,
    qty: 1,
    unit_price: 0,
  }
  receiveForm.value?.clearValidate()
}

async function submit() {
  await receiveForm.value?.validate(async (valid) => {
    if (!valid) return
    saving.value = true
    try {
      await api.post('/stock/receive', form.value)
      resetForm()
      await stock.fetch()
      showSuccess('Item received and added to stock.')
    } catch (error) {
      console.error(error)
      showError('Failed to receive item.')
    } finally {
      saving.value = false
    }
  })
}

onMounted(() => {
  items.fetch()
  suppliers.fetch()
  stock.fetch()
})
</script>
