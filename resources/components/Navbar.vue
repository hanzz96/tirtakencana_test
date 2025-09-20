<template>
    <nav class="bg-gray-800 p-4 text-white">
      <ul class="flex space-x-6">
        <li>
          <RouterLink to="/menu-a" class="hover:text-yellow-400" active-class="font-bold text-yellow-400">
            Menu A
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/menu-b" class="hover:text-yellow-400" active-class="font-bold text-yellow-400">
            Menu B
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/menu-c" class="hover:text-yellow-400" active-class="font-bold text-yellow-400">
            Menu C
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/menu-d" class="hover:text-yellow-400" active-class="font-bold text-yellow-400">
            Menu D
          </RouterLink>
        </li>
      </ul>
    </nav>
  </template>
  
  <script setup>
  import { RouterLink } from 'vue-router'
  </script>
  