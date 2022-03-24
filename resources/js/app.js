require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/bootstrap4-light-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css'
import Multiselect from 'vue-multiselect';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';

import moment from "moment";
moment.locale('nl');

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        const myApp = createApp({ render: () => h(app, props) });
        myApp.use(plugin)
        myApp.use(PrimeVue);
        myApp.use(ToastService);

        // Global properties
        myApp.config.globalProperties.$moment = moment;
        // Global components
        myApp.component('multiselect', Multiselect);
        myApp.component('Toast', Toast);

        myApp.mixin({
            methods: {
                route,
                toast(type, title, message) {
                    this.$toast.add({severity: type, summary: title, detail: message, life: 5000});
                }
            }
        });
        myApp.mount(el)
        return myApp;
    },
});

InertiaProgress.init({ color: '#055CFC' }); //#4B5563
