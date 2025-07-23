<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import Card from 'primevue/card';
import Button from 'primevue/button';
import { ref, computed } from 'vue';
import { GradientButton, ActionCard, StatsCard } from '@/components/ui';

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

// Mock data - in real app this would come from props
const stats = ref({
    activeGoals: 3,
    averageProgress: 67,
    totalSaved: 15420,
    monthlyGrowth: 12.5
});

const recentGoals = ref([
    { name: 'Emergency Fund', progress: 85, target: 10000, current: 8500, currency: 'USD' },
    { name: 'Car Savings', progress: 45, target: 25000, current: 11250, currency: 'USD' },
    { name: 'Vacation Fund', progress: 30, target: 5000, current: 1500, currency: 'USD' }
]);

const formatCurrency = (amount, currency = 'USD') => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
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
                                        <div class="text-2xl font-bold text-white">{{ stats.activeGoals }}</div>
                                        <div class="text-blue-100 text-sm">Active Goals</div>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                        <div class="text-2xl font-bold text-white">{{ stats.averageProgress }}%</div>
                                        <div class="text-blue-100 text-sm">Avg Progress</div>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                        <div class="text-2xl font-bold text-white">{{ formatCurrency(stats.totalSaved) }}</div>
                                        <div class="text-blue-100 text-sm">Total Saved</div>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                        <div class="text-2xl font-bold text-white">+{{ stats.monthlyGrowth }}%</div>
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

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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

                <!-- Recent Goals Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Recent Goals -->
                    <Card class="shadow-lg border-0">
                        <template #content>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Goals</h3>
                                    <Button
                                        label="View All"
                                        text
                                        class="p-button-text text-blue-600 dark:text-blue-400"
                                        @click="navigateToGoals"
                                    />
                                </div>
                                
                                <div class="space-y-4">
                                    <div v-for="goal in recentGoals" :key="goal.name" class="group p-4 rounded-xl bg-gray-50 dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors duration-200 cursor-pointer">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ goal.name }}</h4>
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ goal.progress }}%</span>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <div class="w-full bg-gray-200 dark:bg-slate-600 rounded-full h-2">
                                                <div 
                                                    class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                                                    :style="{ width: goal.progress + '%' }"
                                                ></div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600 dark:text-gray-300">{{ formatCurrency(goal.current, goal.currency) }} saved</span>
                                            <span class="text-gray-500 dark:text-gray-400">of {{ formatCurrency(goal.target, goal.currency) }}</span>
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
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Getting Started</h3>
                                
                                <div class="space-y-6">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-sm">
                                            1
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Create Your First Goal</h4>
                                            <p class="text-gray-600 dark:text-gray-300 text-sm">Set up a financial goal for savings, car purchases, house down payments, or any other target.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-xl flex items-center justify-center font-bold text-sm">
                                            2
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Track Monthly Progress</h4>
                                            <p class="text-gray-600 dark:text-gray-300 text-sm">Update your progress monthly to see how you're doing compared to your targets.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-xl flex items-center justify-center font-bold text-sm">
                                            3
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Monitor Your Success</h4>
                                            <p class="text-gray-600 dark:text-gray-300 text-sm">View detailed progress charts and see if you're ahead of schedule or need to adjust.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-600">
                                    <GradientButton
                                        label="Create Your First Goal"
                                        icon="pi pi-plus"
                                        variant="primary"
                                        size="lg"
                                        :full-width="true"
                                        :show-arrow="true"
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
