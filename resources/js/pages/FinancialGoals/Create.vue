<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-900 dark:to-slate-800">
      <div class="p-6 lg:p-8">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center gap-4 mb-6">
            <Button
              icon="pi pi-arrow-left"
              text
              @click="goBack"
            />
            <div>
              <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">Create New Goal</h1>
              <p class="text-gray-600 dark:text-gray-300 text-lg">Set up a new financial goal for this year</p>
            </div>
          </div>
        </div>

        <!-- Form Card -->
        <Card class="shadow-xl border-0 overflow-hidden max-w-4xl mx-auto">
          <template #content>
            <div class="p-8">
              <form @submit.prevent="submitForm" class="space-y-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-slate-600 pb-3">Basic Information</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                      <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Goal Name <span class="text-red-500">*</span>
                      </label>
                      <InputText
                        id="name"
                        v-model="form.name"
                        placeholder="e.g., New Car Fund"
                        class="w-full text-lg"
                        :invalid="!!errors.name"
                      />
                      <small v-if="errors.name" class="text-red-500 font-medium">
                        {{ errors.name }}
                      </small>
                    </div>

                    <div class="space-y-3">
                      <label for="category_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Category <span class="text-red-500">*</span>
                      </label>
                      <Dropdown
                        id="category_id"
                        v-model="form.category_id"
                        :options="categories"
                        option-label="display_name"
                        option-value="id"
                        placeholder="Select category"
                        class="w-full"
                        :invalid="!!errors.category_id"
                      />
                      <small v-if="errors.category_id" class="text-red-500 font-medium">
                        {{ errors.category_id }}
                      </small>
                    </div>
                  </div>

                  <div class="space-y-3">
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                      Description
                    </label>
                    <Textarea
                      id="description"
                      v-model="form.description"
                      placeholder="Describe your goal and motivation..."
                      rows="3"
                      class="w-full"
                      :invalid="!!errors.description"
                    />
                    <small v-if="errors.description" class="text-red-500 font-medium">
                      {{ errors.description }}
                    </small>
                  </div>
                </div>

                <!-- Financial Details -->
                <div class="space-y-6">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-slate-600 pb-3">Financial Details</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                        placeholder="Select currency"
                        class="w-full"
                        :invalid="!!errors.currency_id"
                      />
                      <small v-if="errors.currency_id" class="text-red-500 font-medium">
                        {{ errors.currency_id }}
                      </small>
                    </div>

                    <div class="space-y-3">
                      <label for="start_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Starting Amount <span class="text-red-500">*</span>
                      </label>
                      <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg">{{ selectedCurrencySymbol }}</span>
                        <InputNumber
                          id="start_amount"
                          v-model="form.start_amount"
                          placeholder="0.00"
                          :min-fraction-digits="selectedCurrencyDecimals"
                          :max-fraction-digits="selectedCurrencyDecimals"
                          class="w-full pl-8 text-lg"
                          :invalid="!!errors.start_amount"
                        />
                      </div>
                      <small v-if="errors.start_amount" class="text-red-500 font-medium">
                        {{ errors.start_amount }}
                      </small>
                    </div>

                    <div class="space-y-3">
                      <label for="target_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Target Amount <span class="text-red-500">*</span>
                      </label>
                      <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg">{{ selectedCurrencySymbol }}</span>
                        <InputNumber
                          id="target_amount"
                          v-model="form.target_amount"
                          placeholder="0.00"
                          :min-fraction-digits="selectedCurrencyDecimals"
                          :max-fraction-digits="selectedCurrencyDecimals"
                          class="w-full pl-8 text-lg"
                          :invalid="!!errors.target_amount"
                        />
                      </div>
                      <small v-if="errors.target_amount" class="text-red-500 font-medium">
                        {{ errors.target_amount }}
                      </small>
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                      <label for="expected_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Expected Amount (Optional)
                      </label>
                      <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg">{{ selectedCurrencySymbol }}</span>
                        <InputNumber
                          id="expected_amount"
                          v-model="form.expected_amount"
                          placeholder="0.00"
                          :min-fraction-digits="selectedCurrencyDecimals"
                          :max-fraction-digits="selectedCurrencyDecimals"
                          class="w-full pl-8 text-lg"
                          :invalid="!!errors.expected_amount"
                        />
                      </div>
                      <small v-if="errors.expected_amount" class="text-red-500 font-medium">
                        {{ errors.expected_amount }}
                      </small>
                    </div>

                    <div class="space-y-3">
                      <label for="year" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Target Year <span class="text-red-500">*</span>
                      </label>
                      <Dropdown
                        id="year"
                        v-model="form.year"
                        :options="yearOptions"
                        option-label="label"
                        option-value="value"
                        placeholder="Select year"
                        class="w-full"
                        :invalid="!!errors.year"
                      />
                      <small v-if="errors.year" class="text-red-500 font-medium">
                        {{ errors.year }}
                      </small>
                    </div>
                  </div>
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
                    label="Create Goal"
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
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Card from 'primevue/card'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
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
    title: 'Create Goal',
    href: '/financial-goals/create',
  },
]

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

const selectedCurrencySymbol = computed(() => {
  if (!form.value.currency_id) return '$'
  const currency = props.currencies.find(c => c.id === form.value.currency_id)
  return currency?.symbol || '$'
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
  router.post('/financial-goals', form.value)
}

const goBack = () => {
  router.get('/financial-goals')
}
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