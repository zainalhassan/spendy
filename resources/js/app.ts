import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';

// PrimeVue imports
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import 'primeicons/primeicons.css';

// Pinia imports
import { createPinia } from 'pinia';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        app.use(plugin)
           .use(ZiggyVue)
           .use(PrimeVue, {
               theme: {
                   preset: Aura,
                   options: {
                       prefix: 'p',
                       darkModeSelector: '.dark',
                       cssLayer: false
                   }
               },
           })
           .use(createPinia())
           .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
