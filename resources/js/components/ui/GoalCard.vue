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
    
    <!-- Content -->
    <div class="p-6">
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="text-center p-3 bg-gray-50 dark:bg-slate-700 rounded-xl">
          <div class="text-sm text-gray-600 dark:text-gray-300 mb-1">Current</div>
          <div class="font-bold text-gray-900 dark:text-white">{{ goal.formatted_current_amount }}</div>
        </div>
        <div class="text-center p-3 bg-gray-50 dark:bg-slate-700 rounded-xl">
          <div class="text-sm text-gray-600 dark:text-gray-300 mb-1">Target</div>
          <div class="font-bold text-gray-900 dark:text-white">{{ goal.formatted_target_amount }}</div>
        </div>
      </div>
      
      <!-- Footer -->
      <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-slate-700">
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ goal.year }}</span>
        <div class="flex gap-2">
          <Button
            icon="pi pi-eye"
            size="small"
            text
            class="p-button-text text-blue-600 dark:text-blue-400"
            @click.stop="$emit('view')"
          />
          <Button
            icon="pi pi-pencil"
            size="small"
            text
            class="p-button-text text-gray-600 dark:text-gray-400"
            @click.stop="$emit('edit')"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Button from 'primevue/button'
import Tag from 'primevue/tag'

defineProps({
  goal: {
    type: Object,
    required: true
  }
})

defineEmits(['click', 'view', 'edit'])
</script> 