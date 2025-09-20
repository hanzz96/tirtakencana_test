<template>
  <div class="min-h-screen bg-gray-100">
    <Navbar />
    <div class="p-6">
      <RouterView />
    </div>
  </div>
</template>

<script setup>
import Navbar from '../components/Navbar.vue'
import { RouterView } from 'vue-router'
import 'vue3-toastify/dist/index.css'
</script>
