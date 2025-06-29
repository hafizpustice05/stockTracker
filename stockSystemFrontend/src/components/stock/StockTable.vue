<template>
  <el-card
    ><h3>Stock</h3>
    <el-table
      :data="sortData"
      @sort-change="onSort"
      style="width: 100%"
      :default-sort="{ prop: 'price', order: 'ascending' }"
    >
      <el-table-column prop="name" label="Item" />
      <el-table-column prop="qty" label="Qty" width="100" sortable="custom" />
      <el-table-column prop="last_cost" label="Total Cost" width="120" sortable="custom" />
    </el-table>
  </el-card>
</template>
<script setup>
import { computed, ref, onMounted } from 'vue'
import { useStock } from '@/stores/useStock'

const stock = useStock()
const sort = ref({ prop: 'last_cost', order: 'ascending' })
const onSort = (s) => (sort.value = s)
const sortData = computed(() => {
  const k = sort.value.prop,
    dir = sort.value.order === 'descending' ? -1 : 1
  return [...stock.list].sort((a, b) => (a[k] > b[k] ? dir : -dir))
})
onMounted(stock.fetch)
</script>
