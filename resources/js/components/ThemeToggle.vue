<template>
  <div class="flex items-center gap-2">
    <Button
      :icon="appearance === 'dark' ? 'pi pi-sun' : 'pi pi-moon'"
      text
      @click="toggleTheme"
      :class="appearance === 'dark' ? 'text-yellow-400' : 'text-gray-600'"
      :title="appearance === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAppearance } from '@/composables/useAppearance'
import Button from 'primevue/button'

const { appearance, updateAppearance } = useAppearance()

const toggleTheme = () => {
  const newTheme = appearance.value === 'dark' ? 'light' : 'dark'
  updateAppearance(newTheme)
}
</script> 