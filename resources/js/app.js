import { createApp } from 'vue';
import '../css/app.css';
import './bootstrap';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Create a standard Vue application
import ViewAjax from './components/ViewAjax.vue';
createApp({
    components: {
        ViewAjax,
    },
    setup() {
        return {};
    },
})
    .component('ViewAjax', ViewAjax)
    .mount('#app');

// createInertiaApp({
//     title: (title) => `${title} - ${appName}`,
//     resolve: (name) =>
//         resolvePageComponent(
//             `./Pages/${name}.vue`,
//             import.meta.glob('./Pages/**/*.vue'),
//         ),
//     setup({ el, App, props, plugin }) {
//         const app = createApp({ render: () => h(App, props) })
//             .use(plugin)
//             .use(ZiggyVue);

//         // Register the component globally
//         app.component('ViewAjax', ViewAjax);

//         app.mount(el);
//     },
//     progress: {
//         color: '#4B5563',
//     },
// });
