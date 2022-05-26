require('./bootstrap');
import { createApp } from 'vue';

//Components
const app=createApp({});
app.component('lobby-selection', require('./components/LobbySelection.vue').default);
app.component('register-player', require('./components/RegisterPlayer.vue').default);
app.component('game-screen', require('./components/GameScreen.vue').default);
app.mount('#app');