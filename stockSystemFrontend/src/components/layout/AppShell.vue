<template>
  <div class="min-h-screen bg-gray-100">
    <Navbar />
    <div class="p-4 max-w-6xl mx-auto">
      <router-view />
    </div>
  </div>
</template>

<script setup>
import Navbar from './Navbar.vue'
</script>
