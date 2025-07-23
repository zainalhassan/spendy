<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-900 dark:to-slate-800">
      <div class="p-6 lg:p-8">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">Financial Goals</h1>
              <p class="text-gray-600 dark:text-gray-300 text-lg">Track your annual savings and investment goals</p>
            </div>
            <Button
              label="Add New Goal"
              icon="pi pi-plus"
              severity="success"
              size="normal"
              @click="navigateToCreate"
            />
          </div>

          <!-- Year Selector -->
          <div class="flex items-center gap-4">
            <label for="year" class="text-sm font-medium text-gray-700 dark:text-gray-300">Year:</label>
            <Dropdown
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
            :value="summary.active_goals_count || 0"
            label="Active Goals"
            icon="pi pi-target"
            color="blue"
          />
          
          <StatsCard
            :value="`${(summary.average_progress || 0).toFixed(1)}%`"
            label="Average Progress"
            icon="pi pi-chart-line"
            color="green"
          />
          
          <StatsCard
            :value="`${(summary.average_expected || 0).toFixed(1)}%`"
            label="Expected Progress"
            icon="pi pi-calendar"
            color="purple"
          />
        </div>

        <!-- Goals Grid -->
        <div v-if="goals.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <GoalCard
            v-for="goal in goals"
            :key="goal.id"
            :goal="goal"
            @click="viewGoal(goal)"
            @view="viewGoal(goal)"
            @edit="editGoal(goal)"
          />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <div class="w-24 h-24 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="pi pi-target text-3xl text-gray-400 dark:text-gray-500"></i>
          </div>
          <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">No goals yet</h3>
          <p class="text-gray-600 dark:text-gray-300 mb-6">Create your first financial goal to start tracking your progress</p>
          <Button
            label="Create Your First Goal"
            icon="pi pi-plus"
            severity="primary"
            size="large"
            @click="navigateToCreate"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import { StatsCard, GoalCard } from '@/components/ui'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
  goals: {
    type: Array,
    default: () => []
  },
  summary: {
    type: Object,
    default: () => ({
      active_goals_count: 0,
      average_progress: 0,
      average_expected: 0
    })
  },
  currentYear: {
    type: Number,
    default: new Date().getFullYear()
  }
})

const breadcrumbs = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Financial Goals',
    href: '/financial-goals',
  },
]

const selectedYear = ref(props.currentYear)

const yearOptions = computed(() => {
  const currentYear = new Date().getFullYear()
  const years = []
  for (let year = currentYear + 2; year >= currentYear - 2; year--) {
    years.push({
      label: year.toString(),
      value: year
    })
  }
  return years
})

const navigateToCreate = () => {
  router.get('/financial-goals/create')
}

const viewGoal = (goal) => {
  router.get(`/financial-goals/${goal.id}`)
}

const editGoal = (goal) => {
  router.get(`/financial-goals/${goal.id}/edit`)
}

const onYearChange = () => {
  router.get('/financial-goals', { year: selectedYear.value }, {
    preserveState: true,
    preserveScroll: true
  })
}

onMounted(() => {
  selectedYear.value = props.currentYear
})
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