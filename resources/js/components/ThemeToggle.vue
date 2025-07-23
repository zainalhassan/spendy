<template>
  <div class="flex items-center gap-2">
    <Button
      :icon="isDark ? 'pi pi-sun' : 'pi pi-moon'"
      text
      @click="toggleTheme"
      :class="isDark ? 'text-yellow-400' : 'text-gray-600'"
      :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Button from 'primevue/button'

const isDark = ref(false)

const toggleTheme = () => {
  isDark.value = !isDark.value
  
  // Toggle PrimeVue's dark mode
  if (isDark.value) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
  
  // Store preference in localStorage
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
}

onMounted(() => {
  // Check for saved theme preference or default to system preference
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme) {
    isDark.value = savedTheme === 'dark'
    if (isDark.value) {
      document.documentElement.classList.add('dark')
    }
  } else {
    // Check system preference
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    isDark.value = prefersDark
    if (isDark.value) {
      document.documentElement.classList.add('dark')
    }
  }
})
</script> 