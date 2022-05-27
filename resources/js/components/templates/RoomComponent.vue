<script setup>
const props = defineProps({
    'name': String,
    'players': Number,
    'maxplayers': Number,
    'hasPassword': Boolean
});
</script>

<template>
<div class="card card-general mb-2">
    <div class="row g-0">
        <div class="col-7">
            <div class="card-body">
                <h5 class="card-title">{{ props.name }}</h5>
                <p class="card-text"><small class="text-muted">Players: {{ props.players }} / {{ props.maxplayers }}</small></p>
            </div>
        </div>
        <div class="col-1 m-auto">
            <a>
                <i v-if="props.hasPassword" class="fa fa-lock text-success" aria-hidden="true"></i>
                <i v-else class="fa fa-unlock text-muted" aria-hidden="true"></i>
            </a>
        </div>
        <div class="col-4 m-auto pe-3">
            <button type="button" class="btn btn-lg btn-block btn-primary" :disabled="props.players===props.maxplayers ? 'true': null">Join</button>
        </div>
    </div>
</div>
</template>