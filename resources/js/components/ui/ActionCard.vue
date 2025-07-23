<template>
  <div 
    class="group relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1"
    @click="$emit('click')"
  >
    <div :class="`absolute inset-0 bg-gradient-to-br ${gradientOverlay} opacity-0 group-hover:opacity-100 transition-opacity duration-300`"></div>
    <div class="relative p-6">
      <div class="flex items-center justify-between mb-4">
        <div :class="`w-12 h-12 bg-gradient-to-br ${iconGradient} rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300`">
          <i :class="icon" class="text-xl text-white"></i>
        </div>
        <i :class="`pi pi-arrow-right text-gray-400 group-hover:text-${arrowColor} transition-colors duration-300 group-hover:translate-x-1`"></i>
      </div>
      <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ title }}</h3>
      <p class="text-gray-600 dark:text-gray-300 mb-4">{{ description }}</p>
      <div :class="`flex items-center text-${textColor} dark:text-${textColorDark} font-medium group-hover:text-${textColorHover} dark:group-hover:text-${textColorHoverDark} transition-colors duration-300`">
        <span>{{ actionText }}</span>
        <i class="pi pi-arrow-right ml-2 text-sm group-hover:translate-x-1 transition-transform duration-300"></i>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    required: true
  },
  actionText: {
    type: String,
    required: true
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'success', 'warning', 'danger'].includes(value)
  }
})

defineEmits(['click'])

const variantConfig = {
  primary: {
    gradientOverlay: 'from-blue-500/5 to-purple-500/5',
    iconGradient: 'from-blue-500 to-blue-600',
    arrowColor: 'blue-500',
    textColor: 'blue-600',
    textColorDark: 'blue-400',
    textColorHover: 'blue-700',
    textColorHoverDark: 'blue-300'
  },
  success: {
    gradientOverlay: 'from-green-500/5 to-emerald-500/5',
    iconGradient: 'from-green-500 to-emerald-600',
    arrowColor: 'green-500',
    textColor: 'green-600',
    textColorDark: 'green-400',
    textColorHover: 'green-700',
    textColorHoverDark: 'green-300'
  },
  warning: {
    gradientOverlay: 'from-yellow-500/5 to-orange-500/5',
    iconGradient: 'from-yellow-500 to-orange-600',
    arrowColor: 'yellow-500',
    textColor: 'yellow-600',
    textColorDark: 'yellow-400',
    textColorHover: 'yellow-700',
    textColorHoverDark: 'yellow-300'
  },
  danger: {
    gradientOverlay: 'from-red-500/5 to-pink-500/5',
    iconGradient: 'from-red-500 to-pink-600',
    arrowColor: 'red-500',
    textColor: 'red-600',
    textColorDark: 'red-400',
    textColorHover: 'red-700',
    textColorHoverDark: 'red-300'
  }
}

const config = variantConfig[props.variant]
const { gradientOverlay, iconGradient, arrowColor, textColor, textColorDark, textColorHover, textColorHoverDark } = config
</script> 