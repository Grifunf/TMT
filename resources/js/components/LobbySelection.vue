<script setup>
import { ref, onMounted } from 'vue';
import RoomComponent from './templates/RoomComponent.vue';
import CreateLobby from './templates/CreateLobby.vue';

const allgames = [];
const games = ref([
//    { 'name': 'Test', 'players': 1, 'maxplayers': 5, 'hasPassword': false },
]);

onMounted(() => {
    Echo.channel('lobby')
        .listen('GameAdded', addGame)
        .listen('GameUpdate', updateGame);

    AsyncRequest('get', '/api/lobby', {}, (resp) => {
        resp.forEach((game) => { addGame({
            'id': game['id'],
            'name': game['name'],
            'players': game['currplayers'],
            'maxplayers': game['maxplayers'],
            'hasPassword': game['password']
        }); });
    });
});

function addGame(event)
{
    const game = {
        'id': event['id'],
        'name': event['name'],
        'players': 1,
        'maxplayers': event['maxplayers'],
        'hasPassword': event['haspass']
    };
    allgames.push(game);
    games.value.push(game);
}

function updateGame(event)
{

}

</script>

<template>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand">TMT</a>
        <form class="d-flex">
            <input class="form-control me-1" type="search" placeholder="Search lobby" aria-label="Search" style="width: 160px">
            <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
        </form>
    </div>
</nav>

<div class="full-height center">
    <div class="card card-general">
        <h5 class="card-header text-center">Lobbies</h5>
        <div class="card-body">
            <ul class="list-group">
                <li v-for="(obj, index) in games" :key="index" class="list-bare-item">
                    <room-component :name="obj.name"
                        :players="obj.players"
                        :maxplayers="obj.maxplayers"
                        :hasPassword="obj.hasPassword">
                    </room-component>
                </li>
            </ul>
        </div>
    </div>
</div>

<create-lobby/>

<button class="btn-circle" data-bs-toggle="modal" data-bs-target="#create-lobby"><i class="fa fa-plus text-primary" aria-hidden="true"></i></button>
</template>