<script setup>
import { ref } from 'vue';

const nickname = ref(null);
const submitted = ref(false);
function register(event)
{
    event.preventDefault();
    submitted.value = true;
    const value = nickname.value['value'];
    if( value === null || value === '')
        return;
    AsyncRequest('put', '/api/register', { 'nickname': value }, (resp) => {
        if(resp.hasOwnProperty('code') && resp['code'] === 'success')
            redirect('/');
        else//Not suppose to be reached
            console.log(resp['error']);
    });
}

function onkeyup(event) { submitted.value = false; }
</script>

<template>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand">TMT</a>
    </div>
</nav>
<div class="full-height center">
    <div class="center-content">
        <form class="form" :class="submitted ? 'was-validated' : 'needs-validation'" :novalidate="submitted ? null : true">
            <div class="my-4 text-center">
                <h3>Register</h3>
            </div>
            <div class="mb-4">
                <label for="nick" class="form-label">Choose a nickname:</label>
                <input type="text" class="form-control" id="nick" ref="nickname" @keyup="onkeyup" placeholder="Nickname" required>
                <div class="invalid-feedback" for="nick">
                    Please choose a nickname.
                </div>
            </div>
            <div class="mb-5">
                <button type="submit" class="btn btn-block btn-primary" @click="(event) => register(event)">Choose</button>
            </div>
        </form>
    </div>
</div>
</template>