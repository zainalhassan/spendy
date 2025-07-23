<template>
  <div
    class="group bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1 border border-gray-100 dark:border-slate-700 overflow-hidden"
    @click="$emit('click')"
  >
    <!-- Header -->
    <div class="p-6 border-b border-gray-100 dark:border-slate-700">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ goal.name }}</h3>
        <Tag
          :value="goal.category.display_name"
          :severity="goal.category.severity"
          class="text-xs"
        />
      </div>
      
      <!-- Progress Bar -->
      <div class="space-y-3">
        <div class="flex justify-between text-sm">
          <span class="text-gray-600 dark:text-gray-300">Progress</span>
          <span class="font-semibold text-gray-900 dark:text-white">{{ goal.progress_percentage.toFixed(1) }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-slate-600 rounded-full h-3">
          <div 
            class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-300"
            :style="{ width: goal.progress_percentage + '%' }"
          ></div>
        </div>
        
        <div v-if="goal.expected_progress_percentage > 0" class="space-y-2">
          <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-300">Expected</span>
            <span class="font-semibold text-gray-900 dark:text-white">{{ goal.expected_progress_percentage.toFixed(1) }}%</span>
          </div>
          <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2">
            <div 
              class="bg-gray-400 dark:bg-slate-500 h-2 rounded-full"
              :style="{ width: goal.expected_progress_percentage + '%' }"
            ></div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Footer -->
    <div class="p-6 bg-gray-50 dark:bg-slate-700">
      <div class="flex items-center justify-between mb-3">
        <div class="text-sm">
          <span class="text-gray-600 dark:text-gray-300">Current:</span>
          <span class="font-semibold ml-1 text-gray-900 dark:text-white">{{ formatCurrency(goal.current_amount, goal.currency) }}</span>
        </div>
        <div class="text-sm">
          <span class="text-gray-600 dark:text-gray-300">Target:</span>
          <span class="font-semibold ml-1 text-gray-900 dark:text-white">{{ formatCurrency(goal.target_amount, goal.currency) }}</span>
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="text-xs text-gray-500 dark:text-gray-400">
          {{ goal.year }}
        </div>
        <div class="flex items-center gap-2">
          <Button
            icon="pi pi-eye"
            text
            size="small"
            class="p-button-text text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
            @click.stop="$emit('view', goal)"
          />
          <Button
            icon="pi pi-pencil"
            text
            size="small"
            class="p-button-text text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300"
            @click.stop="$emit('edit', goal)"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import Button from 'primevue/button'
import Tag from 'primevue/tag'

const props = defineProps({
  goal: {
    type: Object,
    required: true
  }
})

defineEmits(['click', 'view', 'edit'])

const formatCurrency = (amount, currency) => {
  if (!currency) {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(amount)
  }

  // Use currency object if available
  if (currency.symbol && currency.decimals !== undefined) {
    const formatted = new Intl.NumberFormat('en-US', {
      minimumFractionDigits: currency.decimals,
      maximumFractionDigits: currency.decimals
    }).format(amount)
    
    return currency.symbol + formatted
  }

  // Fallback to currency code
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: currency.code || 'USD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}
</script> 