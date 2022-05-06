<script setup>
import { ref, onMounted } from 'vue';

const title = ref('Test');
onMounted(() => {
    Echo.channel('test')
        .listen('TestMessage', (event) =>{
            console.log(event['message']);
        });
});
</script>

<template>
    <div class="container">
        <h1>{{ title }}</h1>
    </div>
</template>