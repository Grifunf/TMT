<script setup>
const props = defineProps({
    'res': String,
    'ammount': Number,
    'rewards': Array
});
</script>
<template>
<div class="card">
    <div class="card-body project-card">
        <div v-if="props.res === 'megacredit'" class="pay">
            <img class="project-icon" :src="'/assets/resources/megacredit.png'" :alt="'megacreadit'">
            <div class="centered"><b>{{ props.ammount }}</b></div>
        </div>
        <b v-if="props.res !== 'megacredit'">{{props.ammount}}</b>
        <img v-if="props.res !== 'megacredit'" class="project-icon" :src="`/assets/resources/${props.res}.png`" :alt="props.res">
        <img class="arrow-icon" :src="'/assets/resources/arrow.png'" alt="arrow">
        <img class="project-icon" v-for="(reward, index) in rewards" :key="index" :src="`/assets/resources/${reward}.png`" :alt="reward">
    </div>
</div>
</template>