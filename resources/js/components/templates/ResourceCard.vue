<script setup>
const props = defineProps({
    'res': String,
    'ammount': Number,
    'hasProduction': { 'type': Boolean, 'default': true },
    'production': { 'type': Number, 'default': 0 }
});
</script>

<template>
<div class="card card-res">
    <div class="reslogo mt-2">
        <img :src="`/assets/resources/${props.res}.png`" class="card-img-top" :alt="props.res">
    </div>
    <div class="card-body text-center">
        <h5 class="card-title">{{ props.ammount }}</h5>
        <h5 v-if="hasProduction" class="card-title">{{ props.production }}</h5>
        <a v-if="!hasProduction" class="text-danger">-1</a>
        <a v-if="!hasProduction" class="text-success">+1</a>
    </div>
</div>
</template>