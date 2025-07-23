<template>
  <div class="p-4 md:p-6">
    <div class="mb-6">
      <div class="flex items-center gap-4 mb-4">
        <Button
          icon="pi pi-arrow-left"
          text
          @click="goBack"
          class="p-button-secondary"
        />
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ goal.goal.name }}</h1>
          <p class="text-gray-600">{{ goal.goal.description || 'No description provided' }}</p>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Goal Details -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Progress Overview -->
        <Card class="shadow-sm">
          <template #header>
            <h3 class="text-lg font-semibold text-gray-900">Progress Overview</h3>
          </template>
          <template #content>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
              <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">
                  {{ goal.goal.formatted_current_amount }}
                </div>
                <div class="text-sm text-gray-600">Current Amount</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-green-600">
                  {{ goal.goal.formatted_target_amount }}
                </div>
                <div class="text-sm text-gray-600">Target Amount</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">
                  {{ goal.progress_percentage.toFixed(1) }}%
                </div>
                <div class="text-sm text-gray-600">Progress</div>
              </div>
            </div>

            <div class="space-y-4">
              <div>
                <div class="flex justify-between text-sm mb-2">
                  <span class="text-gray-600">Your Progress</span>
                  <span class="font-semibold">{{ goal.progress_percentage.toFixed(1) }}%</span>
                </div>
                <ProgressBar
                  :value="goal.progress_percentage"
                  :show-value="false"
                  class="h-3"
                />
              </div>

              <div v-if="goal.expected_percentage > 0">
                <div class="flex justify-between text-sm mb-2">
                  <span class="text-gray-600">Expected Progress</span>
                  <span class="font-semibold">{{ goal.expected_percentage.toFixed(1) }}%</span>
                </div>
                <ProgressBar
                  :value="goal.expected_percentage"
                  :show-value="false"
                  class="h-2"
                  style="background-color: #e5e7eb;"
                />
              </div>

              <div class="flex items-center gap-2 mt-4">
                <Tag
                  v-if="goal.is_ahead_of_schedule"
                  value="Ahead of Schedule"
                  severity="success"
                />
                <Tag
                  v-else-if="goal.is_behind_schedule"
                  value="Behind Schedule"
                  severity="warning"
                />
                <Tag
                  v-else
                  value="On Track"
                  severity="info"
                />
              </div>
            </div>
          </template>
        </Card>

        <!-- Progress History -->
        <Card class="shadow-sm">
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-gray-900">Progress History</h3>
              <Button
                label="Update Progress"
                icon="pi pi-plus"
                size="small"
                @click="showProgressDialog = true"
                class="p-button-primary"
              />
            </div>
          </template>
          <template #content>
            <div v-if="goal.monthly_progress.length === 0" class="text-center py-8">
              <i class="pi pi-chart-line text-4xl text-gray-300 mb-4"></i>
              <p class="text-gray-500">No progress updates yet. Add your first update!</p>
            </div>
            <div v-else class="space-y-4">
              <div
                v-for="progress in goal.monthly_progress"
                :key="progress.id"
                class="flex items-center justify-between p-4 border rounded-lg"
              >
                <div>
                  <div class="font-semibold text-gray-900">
                    {{ getMonthName(progress.month) }} {{ progress.year }}
                  </div>
                  <div class="text-sm text-gray-600">
                    {{ formatProgressAmount(progress.current_amount) }}
                  </div>
                  <div v-if="progress.notes" class="text-sm text-gray-500 mt-1">
                    {{ progress.notes }}
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-sm text-gray-500">
                    {{ formatDate(progress.created_at) }}
                  </div>
                </div>
              </div>
            </div>
          </template>
        </Card>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Goal Info -->
        <Card class="shadow-sm">
          <template #header>
            <h3 class="text-lg font-semibold text-gray-900">Goal Details</h3>
          </template>
          <template #content>
            <div class="space-y-4">
              <div>
                <label class="text-sm font-medium text-gray-700">Category</label>
                <div class="mt-1">
                  <Tag
                    :value="goal.goal.category.display_name"
                    :severity="goal.goal.category.severity"
                  />
                </div>
              </div>
              
              <div>
                <label class="text-sm font-medium text-gray-700">Year</label>
                <div class="mt-1 text-gray-900">{{ goal.goal.year }}</div>
              </div>
              
              <div>
                <label class="text-sm font-medium text-gray-700">Starting Amount</label>
                <div class="mt-1 text-gray-900">{{ goal.goal.formatted_start_amount }}</div>
              </div>
              
              <div v-if="goal.goal.expected_amount">
                <label class="text-sm font-medium text-gray-700">Expected Amount</label>
                <div class="mt-1 text-gray-900">{{ goal.goal.formatted_expected_amount }}</div>
              </div>
            </div>
          </template>
        </Card>

        <!-- Actions -->
        <Card class="shadow-sm">
          <template #header>
            <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
          </template>
          <template #content>
            <div class="space-y-3">
              <Button
                label="Edit Goal"
                icon="pi pi-pencil"
                @click="navigateToEdit"
                class="p-button-secondary w-full"
              />
              <Button
                label="Delete Goal"
                icon="pi pi-trash"
                @click="confirmDelete"
                class="p-button-danger w-full"
              />
            </div>
          </template>
        </Card>
      </div>
    </div>

    <!-- Progress Update Dialog -->
    <Dialog
      v-model:visible="showProgressDialog"
      modal
      header="Update Progress"
      :style="{ width: '500px' }"
      :closable="false"
    >
      <form @submit.prevent="submitProgress" class="space-y-4">
        <div class="space-y-2">
          <label for="current_amount" class="block text-sm font-medium text-gray-700">
            Current Amount *
          </label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">{{ selectedCurrencySymbol }}</span>
            <InputNumber
              id="current_amount"
              v-model="progressForm.current_amount"
              placeholder="0.00"
              :min-fraction-digits="selectedCurrencyDecimals"
              :max-fraction-digits="selectedCurrencyDecimals"
              class="w-full pl-8"
              :class="{ 'p-invalid': progressErrors.current_amount }"
            />
          </div>
          <small v-if="progressErrors.current_amount" class="p-error">
            {{ progressErrors.current_amount }}
          </small>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <label for="month" class="block text-sm font-medium text-gray-700">
              Month *
            </label>
            <Select
              id="month"
              v-model="progressForm.month"
              :options="monthOptions"
              placeholder="Select month"
              class="w-full"
              :class="{ 'p-invalid': progressErrors.month }"
            />
            <small v-if="progressErrors.month" class="p-error">
              {{ progressErrors.month }}
            </small>
          </div>

          <div class="space-y-2">
            <label for="progress_year" class="block text-sm font-medium text-gray-700">
              Year *
            </label>
            <Select
              id="progress_year"
              v-model="progressForm.year"
              :options="yearOptions"
              placeholder="Select year"
              class="w-full"
              :class="{ 'p-invalid': progressErrors.year }"
            />
            <small v-if="progressErrors.year" class="p-error">
              {{ progressErrors.year }}
            </small>
          </div>
        </div>

        <div class="space-y-2">
          <label for="notes" class="block text-sm font-medium text-gray-700">
            Notes
          </label>
          <Textarea
            id="notes"
            v-model="progressForm.notes"
            placeholder="Optional notes about this progress update"
            rows="3"
            class="w-full"
          />
        </div>
      </form>

      <template #footer>
        <div class="flex gap-2">
          <Button
            label="Cancel"
            icon="pi pi-times"
            @click="showProgressDialog = false"
            class="p-button-secondary"
          />
          <Button
            label="Update Progress"
            icon="pi pi-check"
            @click="submitProgress"
            :loading="progressLoading"
            class="p-button-primary"
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
      <p class="mb-4">Are you sure you want to delete this goal? This action cannot be undone.</p>
      
      <template #footer>
        <div class="flex gap-2">
          <Button
            label="Cancel"
            icon="pi pi-times"
            @click="showDeleteDialog = false"
            class="p-button-secondary"
          />
          <Button
            label="Delete"
            icon="pi pi-trash"
            @click="deleteGoal"
            :loading="deleteLoading"
            class="p-button-danger"
          />
        </div>
      </template>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import Card from 'primevue/card'
import Button from 'primevue/button'
import ProgressBar from 'primevue/progressbar'
import Tag from 'primevue/tag'
import Dialog from 'primevue/dialog'
import InputNumber from 'primevue/inputnumber'
import Select from 'primevue/select'
import Textarea from 'primevue/textarea'

const props = defineProps({
  goal: {
    type: Object,
    required: true
  }
})

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
  return new Date(dateString).toLocaleDateString()
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
</style> 