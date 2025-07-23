<template>
  <button
    @click="$emit('click')"
    :class="[
      'group relative font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 border-0 focus:outline-none focus:ring-4',
      sizeClasses[size],
      variantClasses[variant],
      { 'w-full': fullWidth }
    ]"
  >
    <div class="flex items-center justify-center gap-2">
      <div v-if="icon" class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors duration-300">
        <i :class="icon" class="text-white text-xs"></i>
      </div>
      <span class="text-lg">{{ label }}</span>
      <div v-if="showArrow" class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors duration-300">
        <i class="pi pi-arrow-right text-white text-xs group-hover:translate-x-0.5 transition-transform duration-300"></i>
      </div>
    </div>
    <!-- Shine effect -->
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
  </button>
</template>

<script setup>
defineProps({
  label: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    default: null
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'success', 'warning', 'danger'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  fullWidth: {
    type: Boolean,
    default: false
  },
  showArrow: {
    type: Boolean,
    default: false
  }
})

defineEmits(['click'])

const variantClasses = {
  primary: 'bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 focus:ring-blue-500/20',
  success: 'bg-gradient-to-r from-green-500 via-emerald-600 to-teal-600 hover:from-emerald-600 hover:via-teal-600 hover:to-cyan-600 focus:ring-green-500/20',
  warning: 'bg-gradient-to-r from-yellow-500 via-orange-600 to-red-600 hover:from-orange-600 hover:via-red-600 hover:to-pink-600 focus:ring-yellow-500/20',
  danger: 'bg-gradient-to-r from-red-500 via-red-600 to-pink-600 hover:from-red-600 hover:via-pink-600 hover:to-purple-600 focus:ring-red-500/20'
}

const sizeClasses = {
  sm: 'py-2 px-4 text-sm',
  md: 'py-3 px-6 text-base',
  lg: 'py-4 px-8 text-lg'
}
</script> 