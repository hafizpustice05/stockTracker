<template>
  <el-popconfirm :title="title" @confirm="$emit('confirm')" @cancel="$emit('cancel')">
    <template #reference>
      <el-button :type="type" :size="size">
        <slot />
      </el-button>
    </template>
  </el-popconfirm>
</template>

<script setup lang="ts">
defineProps<{
  type?: 'primary' | 'success' | 'danger' | 'warning' | 'info'
  size?: 'small' | 'default' | 'large'
  title?: string
}>()
</script>
