<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <div class="p-6 lg:p-8">
      <!-- Header Section -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">Financial Goals</h1>
            <p class="text-gray-600 dark:text-gray-300 text-lg">Track your annual savings and investment goals</p>
          </div>
          <GradientButton
            label="Add New Goal"
            icon="pi pi-plus"
            variant="success"
            size="md"
            @click="navigateToCreate"
          />
        </div>

        <!-- Year Selector -->
        <div class="flex items-center gap-4">
          <label for="year" class="text-sm font-medium text-gray-700 dark:text-gray-300">Year:</label>
          <Select
            v-model="selectedYear"
            :options="yearOptions"
            option-label="label"
            option-value="value"
            placeholder="Select Year"
            class="w-32"
            @change="onYearChange"
          />
        </div>
      </div>

      <!-- Summary Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <StatsCard
          :value="summary.active_goals_count"
          label="Active Goals"
          icon="pi pi-target"
          color="blue"
        />
        
        <StatsCard
          :value="`${summary.average_progress.toFixed(1)}%`"
          label="Average Progress"
          icon="pi pi-chart-line"
          color="green"
        />
        
        <StatsCard
          :value="`${summary.average_expected.toFixed(1)}%`"
          label="Expected Progress"
          icon="pi pi-calendar"
          color="purple"
        />
      </div>

      <!-- Goals List -->
      <div v-if="summary.goals.length === 0" class="text-center py-16">
        <div class="max-w-md mx-auto">
          <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <i class="pi pi-chart-line text-3xl text-white"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No goals yet</h3>
          <p class="text-gray-600 dark:text-gray-300 mb-8 text-lg">Start tracking your financial goals by creating your first one.</p>
          <GradientButton
            label="Create Your First Goal"
            icon="pi pi-plus"
            variant="primary"
            size="lg"
            :show-arrow="true"
            @click="navigateToCreate"
          />
        </div>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <GoalCard
          v-for="goal in summary.goals"
          :key="goal.id"
          :goal="goal"
          @click="navigateToGoal(goal.id)"
          @view="navigateToGoal(goal.id)"
          @edit="navigateToEdit(goal.id)"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Select from 'primevue/select'
import ProgressBar from 'primevue/progressbar'
import Tag from 'primevue/tag'
import { GradientButton, StatsCard, GoalCard } from '@/components/ui'

const props = defineProps({
  summary: {
    type: Object,
    required: true
  },
  currentYear: {
    type: Number,
    required: true
  }
})

const selectedYear = ref(props.currentYear)

const yearOptions = computed(() => {
  const currentYear = new Date().getFullYear()
  const years = []
  for (let year = currentYear + 2; year >= 2020; year--) {
    years.push({
      label: year.toString(),
      value: year
    })
  }
  return years
})

const getProgressBarClass = (progress, expected) => {
  if (progress >= expected) return 'h-2'
  return 'h-2'
}

const onYearChange = () => {
  router.get('/financial-goals', { year: selectedYear.value })
}

const navigateToCreate = () => {
  router.get('/financial-goals/create')
}

const navigateToGoal = (goalId) => {
  router.get(`/financial-goals/${goalId}`)
}

const navigateToEdit = (goalId) => {
  router.get(`/financial-goals/${goalId}/edit`)
}
</script>

<style scoped>
.p-progressbar {
  border-radius: 0.5rem;
}

.p-progressbar-value {
  border-radius: 0.5rem;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: rgba(156, 163, 175, 0.3);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(156, 163, 175, 0.5);
}

.dark ::-webkit-scrollbar-thumb {
  background: rgba(75, 85, 99, 0.3);
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: rgba(75, 85, 99, 0.5);
}
</style> 