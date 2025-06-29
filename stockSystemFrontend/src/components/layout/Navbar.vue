<template>
  <el-menu mode="horizontal" :router="true" class="shadow bg-white">
    <el-menu-item index="/dashboard">Dashboard</el-menu-item>

    <!-- Admin Links -->
    <template v-if="auth.isAdmin">
      <el-sub-menu index="admin">
        <template #title>Admin</template>
        <el-menu-item index="/admin/items">Items</el-menu-item>
        <el-menu-item index="/admin/suppliers">Suppliers</el-menu-item>
        <el-menu-item index="/admin/requisitions">Requisitions</el-menu-item>
      </el-sub-menu>
    </template>

    <!-- Employee Links -->
    <template v-if="auth.isEmployee">
      <el-sub-menu index="emp">
        <template #title>Employee</template>
        <el-menu-item index="/employee/requisitions/new">New Requisition</el-menu-item>
        <el-menu-item index="/employee/requisitions">My Requisitions</el-menu-item>
      </el-sub-menu>
    </template>

    <!-- Store Exec Links -->
    <template v-if="auth.isExec">
      <el-sub-menu index="store">
        <template #title>Store</template>
        <el-menu-item index="/store/receive">Receive</el-menu-item>
        <el-menu-item index="/store/issue">Issue</el-menu-item>
        <el-menu-item index="/store/stock">Stock</el-menu-item>
      </el-sub-menu>
    </template>

    <el-menu-item class="ml-auto" disabled>
      {{ auth.user?.name }} ({{ auth.user?.role }})
    </el-menu-item>
    <el-menu-item @click="auth.logout" index="logout"> Logout </el-menu-item>
  </el-menu>
</template>

<script setup>
import { useAuth } from '@/stores/useAuth'
const auth = useAuth()
</script>
