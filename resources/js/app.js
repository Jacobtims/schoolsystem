require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/bootstrap4-light-blue/theme.css';//theme
import 'primevue/resources/primevue.min.css';//core css

import moment from "moment";
moment.locale('nl');

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        const myApp = createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } });

        myApp.config.globalProperties.$moment = moment;

        myApp.use(PrimeVue);

        myApp.mount(el)
        return myApp;
    },
});

InertiaProgress.init({ color: '#4B5563' });
