<script setup>
import { ref, onMounted } from 'vue';
import ResourceCard from './templates/ResourceCard.vue';
import ProjectCard from './templates/ProjectCard.vue';
</script>

<template>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand">TMT</a>
    </div>
</nav>

<div class="full-height center">
    <div class="card-general center-contnent">
        <div class="row">
            <div class="col-5">
                <!-- Resources -->
                <resource-card
                    :res="'tr'"
                    :ammount="20"
                    :hasProduction="false">
                </resource-card>
                <div class="row">
                    <div class="col-6">
                        <resource-card
                            :res="'megacredit'"
                            :ammount="0"
                            :production="1">
                        </resource-card>
                        <resource-card
                            :res="'steel'"
                            :ammount="0"
                            :production="1">
                        </resource-card>
                        <resource-card
                            :res="'power'"
                            :ammount="0"
                            :production="1">
                        </resource-card>
                    </div>
                    <div class="col-6">
                        <resource-card
                            :res="'plant'"
                            :ammount="0"
                            :production="1">
                        </resource-card>
                        <resource-card
                            :res="'titanium'"
                            :ammount="0"
                            :production="1">
                        </resource-card>
                        <resource-card
                            :res="'heat'"
                            :ammount="0"
                            :production="1">
                        </resource-card>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <!-- Standard Project -->
                <div class="center-content">
                    <project-card
                        :res="'megacredit'"
                        :ammount="23"
                        :rewards="['greenery']">
                    </project-card>
                    <project-card
                        :res="'megacredit'"
                        :ammount="25"
                        :rewards="['city']">
                    </project-card>
                    <project-card
                        :res="'megacredit'"
                        :ammount="18"
                        :rewards="['ocean']">
                    </project-card>
                    <project-card
                        :res="'megacredit'"
                        :ammount="11"
                        :rewards="['power']">
                    </project-card>
                    <project-card
                        :res="'megacredit'"
                        :ammount="14"
                        :rewards="['heat']">
                    </project-card>
                    <project-card
                        :res="'heat'"
                        :ammount="8"
                        :rewards="['temperature']">
                    </project-card>
                    <project-card
                        :res="'plant'"
                        :ammount="8"
                        :rewards="['greenery']">
                    </project-card>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <button class="btn btn-block btn-primary">Produce</button>
        </div>
        
    </div>
</div>
</template>