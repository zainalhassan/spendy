<template>
  <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-slate-700">
    <div class="flex items-center justify-between">
      <div>
        <div :class="`text-3xl font-bold text-${color} dark:text-${colorDark}`">{{ value }}</div>
        <div class="text-sm text-gray-600 dark:text-gray-300">{{ label }}</div>
      </div>
      <div :class="`w-12 h-12 bg-${color}100 dark:bg-${color}900/30 rounded-xl flex items-center justify-center`">
        <i :class="[icon, `text-${color} dark:text-${colorDark} text-xl`]"></i>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  value: {
    type: [String, Number],
    required: true
  },
  label: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    required: true
  },
  color: {
    type: String,
    default: 'blue',
    validator: (value) => ['blue', 'green', 'purple', 'orange', 'red'].includes(value)
  }
})

const colorDark = computed(() => {
  const colorMap = {
    blue: 'blue-400',
    green: 'green-400',
    purple: 'purple-400',
    orange: 'orange-400',
    red: 'red-400'
  }
  return colorMap[props.color]
})
</script> 