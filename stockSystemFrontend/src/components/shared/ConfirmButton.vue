<!-- <template>
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
</script> -->

<template>
  <el-popconfirm :title="title" @confirm="emitConfirm">
    <template #reference>
      <el-button :type="type" :disabled="disabled">
        <slot />
      </el-button>
    </template>
  </el-popconfirm>
</template>

<script setup>
defineProps({
  type: { type: String, default: 'primary' },
  title: { type: String, default: 'Are you sure?' },
  disabled: { type: Boolean, default: false },
})
const emit = defineEmits(['confirm'])
const emitConfirm = () => emit('confirm')
</script>
