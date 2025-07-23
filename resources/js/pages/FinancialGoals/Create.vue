<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
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
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">Create New Goal</h1>
            <p class="text-gray-600 dark:text-gray-300 text-lg mt-2">Set up a new financial goal to track your progress</p>
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
                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white">
                  Goal Name *
                </label>
                <InputText
                  id="name"
                  v-model="form.name"
                  placeholder="e.g., Emergency Fund, Car Savings"
                  class="w-full"
                  :class="{ 'p-invalid': errors.name }"
                />
                <small v-if="errors.name" class="p-error">{{ errors.name }}</small>
              </div>

              <!-- Description -->
              <div class="space-y-3">
                <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-white">
                  Description
                </label>
                <Textarea
                  id="description"
                  v-model="form.description"
                  placeholder="Optional description of your goal"
                  rows="3"
                  class="w-full"
                  :class="{ 'p-invalid': errors.description }"
                />
                <small v-if="errors.description" class="p-error">{{ errors.description }}</small>
              </div>

              <!-- Category and Currency -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-3">
                  <label for="category" class="block text-sm font-semibold text-gray-900 dark:text-white">
                    Category *
                  </label>
                  <Select
                    id="category"
                    v-model="form.category_id"
                    :options="categoryOptions"
                    option-label="label"
                    option-value="value"
                    placeholder="Select a category"
                    class="w-full"
                    :class="{ 'p-invalid': errors.category_id }"
                  />
                  <small v-if="errors.category_id" class="p-error">{{ errors.category_id }}</small>
                </div>

                <div class="space-y-3">
                  <label for="currency" class="block text-sm font-semibold text-gray-900 dark:text-white">
                    Currency *
                  </label>
                  <Select
                    id="currency"
                    v-model="form.currency_id"
                    :options="currencyOptions"
                    option-label="label"
                    option-value="value"
                    placeholder="Select currency"
                    class="w-full"
                    :class="{ 'p-invalid': errors.currency_id }"
                  />
                  <small v-if="errors.currency_id" class="p-error">{{ errors.currency_id }}</small>
                </div>
              </div>

              <!-- Year -->
              <div class="space-y-3">
                <label for="year" class="block text-sm font-semibold text-gray-900 dark:text-white">
                  Year *
                </label>
                <Select
                  id="year"
                  v-model="form.year"
                  :options="yearOptions"
                  option-label="label"
                  option-value="value"
                  placeholder="Select year"
                  class="w-full"
                  :class="{ 'p-invalid': errors.year }"
                />
                <small v-if="errors.year" class="p-error">{{ errors.year }}</small>
              </div>

              <!-- Amounts Section -->
              <Card class="bg-gray-50 dark:bg-slate-700 border-0">
                <template #content>
                  <div class="p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Targets</h3>
                    
                    <!-- Start and Target Amounts -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                      <div class="space-y-3">
                        <label for="start_amount" class="block text-sm font-semibold text-gray-900 dark:text-white">
                          Starting Amount *
                        </label>
                        <div class="relative">
                          <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">{{ selectedCurrencySymbol }}</span>
                          <InputNumber
                            id="start_amount"
                            v-model="form.start_amount"
                            placeholder="0.00"
                            :min-fraction-digits="selectedCurrencyDecimals"
                            :max-fraction-digits="selectedCurrencyDecimals"
                            class="w-full pl-12"
                            :class="{ 'p-invalid': errors.start_amount }"
                          />
                        </div>
                        <small v-if="errors.start_amount" class="p-error">{{ errors.start_amount }}</small>
                      </div>

                      <div class="space-y-3">
                        <label for="target_amount" class="block text-sm font-semibold text-gray-900 dark:text-white">
                          Target Amount *
                        </label>
                        <div class="relative">
                          <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">{{ selectedCurrencySymbol }}</span>
                          <InputNumber
                            id="target_amount"
                            v-model="form.target_amount"
                            placeholder="0.00"
                            :min-fraction-digits="selectedCurrencyDecimals"
                            :max-fraction-digits="selectedCurrencyDecimals"
                            class="w-full pl-12"
                            :class="{ 'p-invalid': errors.target_amount }"
                          />
                        </div>
                        <small v-if="errors.target_amount" class="p-error">{{ errors.target_amount }}</small>
                      </div>
                    </div>

                    <!-- Expected Amount -->
                    <div class="space-y-3">
                      <label for="expected_amount" class="block text-sm font-semibold text-gray-900 dark:text-white">
                        Expected Amount (Optional)
                      </label>
                      <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">{{ selectedCurrencySymbol }}</span>
                        <InputNumber
                          id="expected_amount"
                          v-model="form.expected_amount"
                          placeholder="0.00"
                          :min-fraction-digits="selectedCurrencyDecimals"
                          :max-fraction-digits="selectedCurrencyDecimals"
                          class="w-full pl-12"
                          :class="{ 'p-invalid': errors.expected_amount }"
                        />
                      </div>
                      <small class="text-gray-600 dark:text-gray-300">
                        This is your expected progress amount for tracking purposes
                      </small>
                      <small v-if="errors.expected_amount" class="p-error">{{ errors.expected_amount }}</small>
                    </div>
                  </div>
                </template>
              </Card>

              <!-- Form Actions -->
              <div class="flex flex-col lg:flex-row gap-4 pt-6 border-t border-gray-200 dark:border-slate-600">
                <Button
                  type="submit"
                  label="Create Goal"
                  icon="pi pi-check"
                  :loading="loading"
                  class="p-button-primary flex-1"
                />
                <Button
                  type="button"
                  label="Cancel"
                  icon="pi pi-times"
                  @click="goBack"
                  class="p-button-secondary flex-1"
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
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import Card from 'primevue/card'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Textarea from 'primevue/textarea'
import Select from 'primevue/select'

const props = defineProps({
  currencies: {
    type: Array,
    default: () => []
  },
  categories: {
    type: Array,
    default: () => []
  }
})

const loading = ref(false)

const form = useForm({
  name: '',
  description: '',
  category_id: null,
  currency_id: null,
  year: new Date().getFullYear(),
  start_amount: null,
  target_amount: null,
  expected_amount: null,
})

// Destructure errors from the form
const { errors } = form

const categoryOptions = computed(() => {
  return props.categories?.map(category => ({
    label: category.display_name,
    value: category.id
  })) || []
})

const currencyOptions = computed(() => {
  return props.currencies?.map(currency => ({
    label: `${currency.name} (${currency.symbol})`,
    value: currency.id
  })) || []
})

const selectedCurrency = computed(() => {
  return props.currencies?.find(c => c.id === form.currency_id) || null
})

const selectedCurrencySymbol = computed(() => {
  return selectedCurrency.value?.symbol || '$'
})

const selectedCurrencyDecimals = computed(() => {
  return selectedCurrency.value?.decimals || 2
})

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

const submitForm = () => {
  loading.value = true
  
  form.post('/financial-goals', {
    onSuccess: () => {
      loading.value = false
    },
    onError: () => {
      loading.value = false
    }
  })
}

const goBack = () => {
  router.get('/financial-goals')
}
</script>

<style scoped>
.p-inputnumber-input {
  padding-left: 3rem;
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