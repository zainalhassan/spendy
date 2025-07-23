<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-900 dark:to-slate-800">
      <div class="p-6 lg:p-8">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="flex items-center gap-4 mb-6">
            <Button
              icon="pi pi-arrow-left"
              text
              @click="goBack"
            />
            <div class="flex-1">
              <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ goal.goal.name }}</h1>
              <p class="text-gray-600 dark:text-gray-300 text-lg">{{ goal.goal.description || 'No description provided' }}</p>
            </div>
            <div class="flex items-center gap-3">
              <Button
                icon="pi pi-pencil"
                text
                @click="navigateToEdit"
                v-tooltip.top="'Edit Goal'"
              />
              <Button
                icon="pi pi-trash"
                text
                @click="confirmDelete"
                severity="danger"
                v-tooltip.top="'Delete Goal'"
              />
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Progress Overview Card -->
            <Card class="shadow-xl border-0 overflow-hidden">
              <template #content>
                <div class="p-8">
                  <!-- Progress Stats Grid -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl">
                      <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                        {{ goal.goal.formatted_current_amount }}
                      </div>
                      <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Current Amount</div>
                    </div>
                    
                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl">
                      <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">
                        {{ goal.goal.formatted_target_amount }}
                      </div>
                      <div class="text-sm font-medium text-green-700 dark:text-green-300">Target Amount</div>
                    </div>
                    
                    <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl">
                      <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">
                        {{ goal.progress_percentage.toFixed(1) }}%
                      </div>
                      <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Progress</div>
                    </div>
                  </div>

                  <!-- Progress Bars -->
                  <div class="space-y-6">
                    <!-- Main Progress -->
                    <div>
                      <div class="flex justify-between items-center mb-3">
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Your Progress</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ goal.progress_percentage.toFixed(1) }}%</span>
                      </div>
                      <div class="relative">
                        <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-4 overflow-hidden">
                          <div 
                            class="bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 h-4 rounded-full transition-all duration-1000 ease-out"
                            :style="{ width: goal.progress_percentage + '%' }"
                          ></div>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse"></div>
                      </div>
                    </div>

                    <!-- Expected Progress -->
                    <div v-if="goal.expected_percentage > 0">
                      <div class="flex justify-between items-center mb-3">
                        <span class="text-lg font-semibold text-gray-700 dark:text-gray-300">Expected Progress</span>
                        <span class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ goal.expected_percentage.toFixed(1) }}%</span>
                      </div>
                      <div class="w-full bg-gray-100 dark:bg-slate-800 rounded-full h-3">
                        <div 
                          class="bg-gradient-to-r from-gray-400 to-gray-500 h-3 rounded-full"
                          :style="{ width: goal.expected_percentage + '%' }"
                        ></div>
                      </div>
                    </div>
                  </div>

                  <!-- Status Indicator -->
                  <div class="mt-8 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-full">
                      <i class="pi pi-info-circle text-blue-600 dark:text-blue-400"></i>
                      <span class="font-semibold text-blue-800 dark:text-blue-300">On Track</span>
                    </div>
                  </div>
                </div>
              </template>
            </Card>

            <!-- Progress History Card -->
            <Card class="shadow-xl border-0 overflow-hidden">
              <template #header>
                <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-slate-700">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                      <i class="pi pi-chart-line text-white text-lg"></i>
                    </div>
                    <div>
                      <h3 class="text-xl font-bold text-gray-900 dark:text-white">Progress History</h3>
                      <p class="text-sm text-gray-600 dark:text-gray-300">Track your monthly progress updates</p>
                    </div>
                  </div>
                  <Button
                    label="Update Progress"
                    icon="pi pi-plus"
                    @click="showProgressDialog = true"
                    severity="primary"
                  />
                </div>
              </template>
              <template #content>
                <div class="p-6">
                  <div v-if="goal.monthly_progress.length === 0" class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 rounded-full flex items-center justify-center mx-auto mb-6">
                      <i class="pi pi-chart-line text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No progress updates yet</h4>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Add your first progress update to start tracking your journey</p>
                    <Button
                      label="Add First Update"
                      icon="pi pi-plus"
                      @click="showProgressDialog = true"
                      severity="primary"
                    />
                  </div>
                  <div v-else class="space-y-4">
                    <div
                      v-for="progress in goal.monthly_progress"
                      :key="progress.id"
                      class="group p-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-slate-800 dark:to-slate-700 rounded-2xl hover:from-gray-100 hover:to-gray-200 dark:hover:from-slate-700 dark:hover:to-slate-600 transition-all duration-300 border border-gray-200 dark:border-slate-600"
                    >
                      <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                          <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ getMonthName(progress.month).substring(0, 3) }}</span>
                          </div>
                          <div>
                            <div class="font-bold text-gray-900 dark:text-white text-lg">
                              {{ getMonthName(progress.month) }} {{ progress.year }}
                            </div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                              {{ formatProgressAmount(progress.current_amount) }}
                            </div>
                            <div v-if="progress.notes" class="text-sm text-gray-600 dark:text-gray-300 mt-1 italic">
                              "{{ progress.notes }}"
                            </div>
                          </div>
                        </div>
                        <div class="text-right">
                          <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ formatDate(progress.created_at) }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </Card>
          </div>

          <!-- Sidebar -->
          <div class="space-y-8">
            <!-- Goal Info Card -->
            <Card class="shadow-xl border-0 overflow-hidden">
              <template #header>
                <div class="flex items-center gap-3 p-6 border-b border-gray-100 dark:border-slate-700">
                  <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="pi pi-info-circle text-white text-lg"></i>
                  </div>
                  <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Goal Details</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Goal information and settings</p>
                  </div>
                </div>
              </template>
              <template #content>
                <div class="p-6 space-y-6">
                  <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Category</label>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-full">
                      <i :class="goal.goal.category.icon" class="text-green-600 dark:text-green-400"></i>
                      <span class="font-semibold text-green-800 dark:text-green-300">{{ goal.goal.category.display_name }}</span>
                    </div>
                  </div>
                  
                  <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Year</label>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ goal.goal.year }}</div>
                  </div>
                  
                  <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Starting Amount</label>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ goal.goal.formatted_start_amount }}</div>
                  </div>
                  
                  <div v-if="goal.goal.expected_amount">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Expected Amount</label>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ goal.goal.formatted_expected_amount }}</div>
                  </div>

                  <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Currency</label>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ goal.goal.currency.name }}</div>
                  </div>
                </div>
              </template>
            </Card>

            <!-- Quick Actions Card -->
            <Card class="shadow-xl border-0 overflow-hidden">
              <template #header>
                <div class="flex items-center gap-3 p-6 border-b border-gray-100 dark:border-slate-700">
                  <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="pi pi-bolt text-white text-lg"></i>
                  </div>
                  <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Manage your goal</p>
                  </div>
                </div>
              </template>
              <template #content>
                <div class="p-6 space-y-4">
                  <Button
                    label="Update Progress"
                    icon="pi pi-plus"
                    @click="showProgressDialog = true"
                    severity="primary"
                    class="w-full"
                  />
                  <Button
                    label="Edit Goal"
                    icon="pi pi-pencil"
                    @click="navigateToEdit"
                    severity="secondary"
                    class="w-full"
                  />
                  <Button
                    label="Delete Goal"
                    icon="pi pi-trash"
                    @click="confirmDelete"
                    severity="danger"
                    class="w-full"
                  />
                </div>
              </template>
            </Card>
          </div>
        </div>
      </div>
    </div>

    <!-- Progress Update Dialog -->
    <Dialog
      v-model:visible="showProgressDialog"
      modal
      header="Update Progress"
      :style="{ width: '500px' }"
      :closable="false"
      class="p-dialog-lg"
    >
      <form @submit.prevent="submitProgress" class="space-y-6">
        <div class="space-y-3">
          <label for="current_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
            Current Amount <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg">{{ selectedCurrencySymbol }}</span>
            <InputNumber
              id="current_amount"
              v-model="progressForm.current_amount"
              placeholder="0.00"
              :min-fraction-digits="selectedCurrencyDecimals"
              :max-fraction-digits="selectedCurrencyDecimals"
              class="w-full pl-8 text-lg"
              :invalid="!!progressErrors.current_amount"
            />
          </div>
          <small v-if="progressErrors.current_amount" class="text-red-500 font-medium">
            {{ progressErrors.current_amount }}
          </small>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-3">
            <label for="month" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
              Month <span class="text-red-500">*</span>
            </label>
            <Dropdown
              id="month"
              v-model="progressForm.month"
              :options="monthOptions"
              option-label="label"
              option-value="value"
              placeholder="Select month"
              class="w-full"
              :invalid="!!progressErrors.month"
            />
            <small v-if="progressErrors.month" class="text-red-500 font-medium">
              {{ progressErrors.month }}
            </small>
          </div>

          <div class="space-y-3">
            <label for="progress_year" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
              Year <span class="text-red-500">*</span>
            </label>
            <Dropdown
              id="progress_year"
              v-model="progressForm.year"
              :options="yearOptions"
              option-label="label"
              option-value="value"
              placeholder="Select year"
              class="w-full"
              :invalid="!!progressErrors.year"
            />
            <small v-if="progressErrors.year" class="text-red-500 font-medium">
              {{ progressErrors.year }}
            </small>
          </div>
        </div>

        <div class="space-y-3">
          <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
            Notes (Optional)
          </label>
          <Textarea
            id="notes"
            v-model="progressForm.notes"
            placeholder="Add any notes about this progress update..."
            rows="3"
            class="w-full"
            :invalid="!!progressErrors.notes"
          />
          <small v-if="progressErrors.notes" class="text-red-500 font-medium">
            {{ progressErrors.notes }}
          </small>
        </div>
      </form>

      <template #footer>
        <div class="flex gap-3">
          <Button
            label="Cancel"
            icon="pi pi-times"
            @click="showProgressDialog = false"
            severity="secondary"
          />
          <Button
            label="Update Progress"
            icon="pi pi-check"
            @click="submitProgress"
            :loading="progressLoading"
            severity="primary"
          />
        </div>
      </template>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <Dialog
      v-model:visible="showDeleteDialog"
      modal
      header="Confirm Delete"
      :style="{ width: '400px' }"
    >
      <div class="text-center py-4">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="pi pi-exclamation-triangle text-2xl text-red-600 dark:text-red-400"></i>
        </div>
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Goal</h4>
        <p class="text-gray-600 dark:text-gray-300">Are you sure you want to delete "{{ goal.goal.name }}"? This action cannot be undone.</p>
      </div>
      
      <template #footer>
        <div class="flex gap-3">
          <Button
            label="Cancel"
            icon="pi pi-times"
            @click="showDeleteDialog = false"
            severity="secondary"
          />
          <Button
            label="Delete"
            icon="pi pi-trash"
            @click="deleteGoal"
            :loading="deleteLoading"
            severity="danger"
          />
        </div>
      </template>
    </Dialog>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputNumber from 'primevue/inputnumber'
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'
import Tooltip from 'primevue/tooltip'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
  goal: {
    type: Object,
    required: true
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
  {
    title: props.goal.goal.name,
    href: `/financial-goals/${props.goal.goal.id}`,
  },
]

const showProgressDialog = ref(false)
const showDeleteDialog = ref(false)
const progressLoading = ref(false)
const deleteLoading = ref(false)

const progressForm = useForm({
  current_amount: null,
  month: new Date().getMonth() + 1,
  year: new Date().getFullYear(),
  notes: '',
})

// Destructure errors from the progress form
const { errors: progressErrors } = progressForm

const monthOptions = [
  { label: 'January', value: 1 },
  { label: 'February', value: 2 },
  { label: 'March', value: 3 },
  { label: 'April', value: 4 },
  { label: 'May', value: 5 },
  { label: 'June', value: 6 },
  { label: 'July', value: 7 },
  { label: 'August', value: 8 },
  { label: 'September', value: 9 },
  { label: 'October', value: 10 },
  { label: 'November', value: 11 },
  { label: 'December', value: 12 },
]

const yearOptions = computed(() => {
  const currentYear = new Date().getFullYear()
  const years = []
  for (let year = currentYear + 1; year >= 2020; year--) {
    years.push({
      label: year.toString(),
      value: year
    })
  }
  return years
})

const selectedCurrencySymbol = computed(() => {
  return props.goal.goal.currency?.symbol || '$'
})

const selectedCurrencyDecimals = computed(() => {
  return props.goal.goal.currency?.decimals || 2
})

const formatProgressAmount = (amount) => {
  const currency = props.goal.goal.currency
  if (!currency) return '$' + amount.toFixed(2)
  
  const formatted = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: currency.decimals,
    maximumFractionDigits: currency.decimals
  }).format(amount)
  
  return currency.symbol + formatted
}

const getMonthName = (month) => {
  const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ]
  return months[month - 1]
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const submitProgress = () => {
  progressLoading.value = true
  
  progressForm.post(`/financial-goals/${props.goal.goal.id}/progress`, {
    onSuccess: () => {
      progressLoading.value = false
      showProgressDialog.value = false
      progressForm.reset()
    },
    onError: () => {
      progressLoading.value = false
    }
  })
}

const confirmDelete = () => {
  showDeleteDialog.value = true
}

const deleteGoal = () => {
  deleteLoading.value = true
  
  router.delete(`/financial-goals/${props.goal.goal.id}`, {
    onSuccess: () => {
      deleteLoading.value = false
      showDeleteDialog.value = false
    },
    onError: () => {
      deleteLoading.value = false
    }
  })
}

const goBack = () => {
  router.get('/financial-goals')
}

const navigateToEdit = () => {
  router.get(`/financial-goals/${props.goal.goal.id}/edit`)
}
</script>

<style scoped>
.p-inputnumber-input {
  padding-left: 2rem;
}

/* Custom animations */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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