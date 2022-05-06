require('./bootstrap');
import { createApp } from 'vue';

//Components
const app=createApp({});
app.component('test-component', require('./components/TestComponent.vue').default);

app.mount('#app');