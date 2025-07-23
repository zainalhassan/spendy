<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import Card from 'primevue/card';
import Button from 'primevue/button';
import { ref, computed } from 'vue';
import { ActionCard, StatsCard } from '@/components/ui';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      active_goals_count: 0,
      average_progress: 0,
      total_saved: 0,
      monthly_growth: 0
    })
  },
  recentGoals: {
    type: Array,
    default: () => []
  }
});

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const navigateToGoals = () => {
    router.get('/financial-goals');
};

const navigateToCreate = () => {
    router.get('/financial-goals/create');
};

const formatCurrency = (amount, currency = 'USD') => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};

const formatCurrencyWithSymbol = (amount, currency) => {
  if (!currency) {
    return formatCurrency(amount, 'USD');
  }

  if (currency.symbol && currency.decimals !== undefined) {
    const formatted = new Intl.NumberFormat('en-US', {
      minimumFractionDigits: currency.decimals,
      maximumFractionDigits: currency.decimals
    }).format(amount);
    
    return currency.symbol + formatted;
  }

  return formatCurrency(amount, currency.code || 'USD');
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen">
            <div class="flex h-full flex-1 flex-col gap-8 p-6 lg:p-8">
                <!-- Hero Section -->
                <Card class="relative overflow-hidden border-0 shadow-none">
                    <template #content>
                        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 p-8 lg:p-12">
                            <div class="absolute inset-0 bg-black/10"></div>
                            <div class="relative z-10">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="pi pi-wallet text-2xl text-white"></i>
                                    </div>
                                    <div>
                                        <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">Welcome back!</h1>
                                        <p class="text-blue-100 text-lg">Let's check your financial progress</p>
                                    </div>
                                </div>
                                
                                <!-- Quick Stats in Hero -->
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
                                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                        <div class="text-2xl font-bold text-white">{{ stats.active_goals_count || 0 }}</div>
                                        <div class="text-blue-100 text-sm">Active Goals</div>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                        <div class="text-2xl font-bold text-white">{{ (stats.average_progress || 0).toFixed(1) }}%</div>
                                        <div class="text-blue-100 text-sm">Avg Progress</div>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                        <div class="text-2xl font-bold text-white">{{ formatCurrency(stats.total_saved || 0) }}</div>
                                        <div class="text-blue-100 text-sm">Total Saved</div>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                        <div class="text-2xl font-bold text-white">+{{ (stats.monthly_growth || 0).toFixed(1) }}%</div>
                                        <div class="text-blue-100 text-sm">This Month</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Decorative elements -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-16 translate-x-16"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12"></div>
                        </div>
                    </template>
                </Card>

                <!-- Action Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <ActionCard
                        title="View Goals"
                        description="Track your financial goals and monitor progress"
                        icon="pi pi-chart-line"
                        action-text="Explore Goals"
                        variant="primary"
                        @click="navigateToGoals"
                    />
                    
                    <ActionCard
                        title="Create Goal"
                        description="Set up a new financial goal for this year"
                        icon="pi pi-plus"
                        action-text="Get Started"
                        variant="success"
                        @click="navigateToCreate"
                    />
                    
                    <ActionCard
                        title="Track Progress"
                        description="Update monthly progress and see trends"
                        icon="pi pi-chart-bar"
                        action-text="View Analytics"
                        variant="warning"
                        @click="navigateToGoals"
                    />
                </div>

                <!-- Recent Activity & Getting Started -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Goals -->
                    <Card class="shadow-lg border-0">
                        <template #content>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Goals</h3>
                                    <Button
                                        label="View All"
                                        text
                                        class="p-button-text text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300"
                                        @click="navigateToGoals"
                                    />
                                </div>
                                
                                <div v-if="recentGoals.length === 0" class="text-center py-8">
                                    <i class="pi pi-target text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No goals yet. Create your first goal to get started!</p>
                                </div>
                                
                                <div v-else class="space-y-4">
                                    <div 
                                        v-for="goal in recentGoals" 
                                        :key="goal.id" 
                                        class="group p-4 rounded-xl bg-gray-50 dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors duration-200 cursor-pointer"
                                        @click="router.get(`/financial-goals/${goal.id}`)"
                                    >
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ goal.name }}</h4>
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ (goal.progress_percentage || 0).toFixed(1) }}%</span>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <div class="w-full bg-gray-200 dark:bg-slate-600 rounded-full h-2">
                                                <div 
                                                    class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                                                    :style="{ width: (goal.progress_percentage || 0) + '%' }"
                                                ></div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600 dark:text-gray-300">{{ formatCurrencyWithSymbol(goal.current_amount || goal.start_amount, goal.currency) }} saved</span>
                                            <span class="text-gray-500 dark:text-gray-400">of {{ formatCurrencyWithSymbol(goal.target_amount, goal.currency) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <!-- Getting Started -->
                    <Card class="shadow-lg border-0">
                        <template #content>
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">Getting Started</h3>
                                
                                <div class="space-y-6">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-sm">
                                            1
                                        </div>
                                        <div>
                                            <h4 class="font-semibold mb-1 text-gray-900 dark:text-white">Create Your First Goal</h4>
                                            <p class="text-gray-600 dark:text-gray-300 text-sm">Set up a financial goal for savings, car purchases, house down payments, or any other target.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-xl flex items-center justify-center font-bold text-sm">
                                            2
                                        </div>
                                        <div>
                                            <h4 class="font-semibold mb-1 text-gray-900 dark:text-white">Track Monthly Progress</h4>
                                            <p class="text-gray-600 dark:text-gray-300 text-sm">Update your progress monthly to see how you're doing compared to your targets.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-xl flex items-center justify-center font-bold text-sm">
                                            3
                                        </div>
                                        <div>
                                            <h4 class="font-semibold mb-1 text-gray-900 dark:text-white">Monitor & Adjust</h4>
                                            <p class="text-gray-600 dark:text-gray-300 text-sm">Review your progress regularly and adjust your goals as needed to stay on track.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-8">
                                    <Button
                                        label="Create Your First Goal"
                                        icon="pi pi-plus"
                                        severity="primary"
                                        size="large"
                                        class="w-full"
                                        @click="navigateToCreate"
                                    />
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Custom scrollbar for webkit browsers */
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

/* Dark mode scrollbar */
.dark ::-webkit-scrollbar-thumb {
    background: rgba(75, 85, 99, 0.3);
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: rgba(75, 85, 99, 0.5);
}
</style>
