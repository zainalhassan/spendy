<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Monitor, Moon, Sun } from 'lucide-vue-next';

const currentTheme = ref<'light' | 'dark' | 'system'>('system');

const tabs = [
    { value: 'light', Icon: Sun, label: 'Light' },
    { value: 'dark', Icon: Moon, label: 'Dark' },
    { value: 'system', Icon: Monitor, label: 'System' },
] as const;

const updateTheme = (theme: 'light' | 'dark' | 'system') => {
    currentTheme.value = theme;
    
    if (theme === 'system') {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    } else if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    
    localStorage.setItem('theme', theme);
};

onMounted(() => {
    const savedTheme = localStorage.getItem('theme') as 'light' | 'dark' | 'system' | null;
    if (savedTheme) {
        currentTheme.value = savedTheme;
        updateTheme(savedTheme);
    } else {
        // Default to system
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDark) {
            document.documentElement.classList.add('dark');
        }
    }
});
</script>

<template>
    <div class="inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800">
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            @click="updateTheme(value)"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                currentTheme === value
                    ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                    : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
            ]"
        >
            <component :is="Icon" class="-ml-1 h-4 w-4" />
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>
</template>
