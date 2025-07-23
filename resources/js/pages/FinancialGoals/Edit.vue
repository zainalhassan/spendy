<template>
  <div class="min-h-screen">
    <div class="p-6 lg:p-8 max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
          <Button
            icon="pi pi-arrow-left"
            text
            @click="goBack"
            class="p-button-text text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
          />
          <div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">Edit Goal</h1>
            <p class="text-gray-600 dark:text-gray-300 text-lg mt-2">Update your financial goal details</p>
          </div>
        </div>
      </div>

      <!-- Form Card -->
      <Card class="shadow-lg border-0">
        <template #content>
          <div class="p-8">
            <form @submit.prevent="submitForm" class="space-y-8">
              <!-- Goal Name -->
              <div class="space-y-3">
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Goal Name <span class="text-red-500">*</span>
                </label>
                <InputText
                  id="name"
                  v-model="form.name"
                  placeholder="e.g., Emergency Fund, Car Savings"
                  class="w-full"
                  :invalid="!!errors.name"
                />
                <small v-if="errors.name" class="text-red-500 font-medium">{{ errors.name }}</small>
              </div>

              <!-- Category -->
              <div class="space-y-3">
                <label for="category_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Category <span class="text-red-500">*</span>
                </label>
                <Dropdown
                  id="category_id"
                  v-model="form.category_id"
                  :options="categories"
                  option-label="name"
                  option-value="id"
                  placeholder="Select a category"
                  class="w-full"
                  :invalid="!!errors.category_id"
                />
                <small v-if="errors.category_id" class="text-red-500 font-medium">{{ errors.category_id }}</small>
              </div>

              <!-- Currency -->
              <div class="space-y-3">
                <label for="currency_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Currency <span class="text-red-500">*</span>
                </label>
                <Dropdown
                  id="currency_id"
                  v-model="form.currency_id"
                  :options="currencies"
                  option-label="name"
                  option-value="id"
                  placeholder="Select a currency"
                  class="w-full"
                  :invalid="!!errors.currency_id"
                />
                <small v-if="errors.currency_id" class="text-red-500 font-medium">{{ errors.currency_id }}</small>
              </div>

              <!-- Target Amount -->
              <div class="space-y-3">
                <label for="target_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Target Amount <span class="text-red-500">*</span>
                </label>
                <InputNumber
                  id="target_amount"
                  v-model="form.target_amount"
                  :min-fraction-digits="selectedCurrencyDecimals"
                  :max-fraction-digits="selectedCurrencyDecimals"
                  placeholder="0.00"
                  class="w-full"
                  :invalid="!!errors.target_amount"
                />
                <small v-if="errors.target_amount" class="text-red-500 font-medium">{{ errors.target_amount }}</small>
              </div>

              <!-- Start Amount -->
              <div class="space-y-3">
                <label for="start_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Starting Amount
                </label>
                <InputNumber
                  id="start_amount"
                  v-model="form.start_amount"
                  :min-fraction-digits="selectedCurrencyDecimals"
                  :max-fraction-digits="selectedCurrencyDecimals"
                  placeholder="0.00"
                  class="w-full"
                  :invalid="!!errors.start_amount"
                />
                <small v-if="errors.start_amount" class="text-red-500 font-medium">{{ errors.start_amount }}</small>
              </div>

              <!-- Expected Amount -->
              <div class="space-y-3">
                <label for="expected_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Expected Amount (Optional)
                </label>
                <InputNumber
                  id="expected_amount"
                  v-model="form.expected_amount"
                  :min-fraction-digits="selectedCurrencyDecimals"
                  :max-fraction-digits="selectedCurrencyDecimals"
                  placeholder="0.00"
                  class="w-full"
                  :invalid="!!errors.expected_amount"
                />
                <small v-if="errors.expected_amount" class="text-red-500 font-medium">{{ errors.expected_amount }}</small>
                <small class="text-gray-500 dark:text-gray-400">This helps you track if you're on schedule with your goal</small>
              </div>

              <!-- Year -->
              <div class="space-y-3">
                <label for="year" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Year <span class="text-red-500">*</span>
                </label>
                <Dropdown
                  id="year"
                  v-model="form.year"
                  :options="yearOptions"
                  option-label="label"
                  option-value="value"
                  placeholder="Select Year"
                  class="w-full"
                  :invalid="!!errors.year"
                />
                <small v-if="errors.year" class="text-red-500 font-medium">{{ errors.year }}</small>
              </div>

              <!-- Description -->
              <div class="space-y-3">
                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Description (Optional)
                </label>
                <Textarea
                  id="description"
                  v-model="form.description"
                  placeholder="Add any additional details about your goal..."
                  rows="4"
                  class="w-full"
                  :invalid="!!errors.description"
                />
                <small v-if="errors.description" class="text-red-500 font-medium">{{ errors.description }}</small>
              </div>

              <!-- Form Actions -->
              <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-slate-600">
                <Button
                  label="Cancel"
                  text
                  @click="goBack"
                />
                <Button
                  type="submit"
                  label="Update Goal"
                  icon="pi pi-check"
                  :loading="processing"
                  severity="success"
                />
              </div>
            </form>
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Card from 'primevue/card'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'

const props = defineProps({
  goal: {
    type: Object,
    required: true
  },
  errors: {
    type: Object,
    default: () => ({})
  },
  processing: {
    type: Boolean,
    default: false
  },
  currencies: {
    type: Array,
    default: () => []
  },
  categories: {
    type: Array,
    default: () => []
  }
})

const form = ref({
  name: '',
  category_id: null,
  currency_id: null,
  target_amount: null,
  start_amount: null,
  expected_amount: null,
  year: new Date().getFullYear(),
  description: ''
})

const selectedCurrencyDecimals = computed(() => {
  if (!form.value.currency_id) return 2
  
  const currency = props.currencies.find(c => c.id === form.value.currency_id)
  return currency?.decimals || 2
})

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

const submitForm = () => {
  router.put(`/financial-goals/${props.goal.id}`, form.value)
}

const goBack = () => {
  router.get(`/financial-goals/${props.goal.id}`)
}

onMounted(() => {
  // Populate form with existing goal data
  form.value = {
    name: props.goal.name || '',
    category_id: props.goal.category_id || null,
    currency_id: props.goal.currency_id || null,
    target_amount: props.goal.target_amount || null,
    start_amount: props.goal.start_amount || null,
    expected_amount: props.goal.expected_amount || null,
    year: props.goal.year || new Date().getFullYear(),
    description: props.goal.description || ''
  }
})
</script>

<style scoped>
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