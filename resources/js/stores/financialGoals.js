import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export const useFinancialGoalsStore = defineStore('financialGoals', {
    state: () => ({
        goals: [],
        currentGoal: null,
        loading: false,
        currentYear: new Date().getFullYear(),
    }),

    getters: {
        activeGoals: (state) => state.goals.filter(goal => goal.is_active),
        goalsByYear: (state) => (year) => state.goals.filter(goal => goal.year === year),
        averageProgress: (state) => {
            const activeGoals = state.goals.filter(goal => goal.is_active);
            if (activeGoals.length === 0) return 0;
            
            const totalProgress = activeGoals.reduce((sum, goal) => sum + goal.progress_percentage, 0);
            return totalProgress / activeGoals.length;
        },
    },

    actions: {
        async fetchGoals(year = null) {
            this.loading = true;
            try {
                const response = await router.get('/financial-goals', { year }, {
                    preserveState: true,
                    preserveScroll: true,
                });
                
                if (response.props.summary) {
                    this.goals = response.props.summary.goals;
                    this.currentYear = response.props.currentYear;
                }
            } catch (error) {
                console.error('Error fetching goals:', error);
            } finally {
                this.loading = false;
            }
        },

        async createGoal(goalData) {
            this.loading = true;
            try {
                await router.post('/financial-goals', goalData);
            } catch (error) {
                console.error('Error creating goal:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateGoal(goalId, goalData) {
            this.loading = true;
            try {
                await router.put(`/financial-goals/${goalId}`, goalData);
            } catch (error) {
                console.error('Error updating goal:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteGoal(goalId) {
            this.loading = true;
            try {
                await router.delete(`/financial-goals/${goalId}`);
            } catch (error) {
                console.error('Error deleting goal:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateProgress(goalId, progressData) {
            this.loading = true;
            try {
                await router.post(`/financial-goals/${goalId}/progress`, progressData);
            } catch (error) {
                console.error('Error updating progress:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        setCurrentGoal(goal) {
            this.currentGoal = goal;
        },

        clearCurrentGoal() {
            this.currentGoal = null;
        },
    },
}); 