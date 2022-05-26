<script setup>
import { ref } from 'vue';

const submitted = ref(false);
const usingPassword = ref(false);

const name = ref(null);
const limit = ref(null);
const password = ref(null);
const cpassword = ref(null);

function create(event)
{
    event.preventDefault();
    submitted.value = true;
    
    const namestr = name.value['value'];
    const limitnumber = limit.value['value'];
    let passwordstr = '';
    let cpasswordstr = '';
    
    if(usingPassword.value)
    {
        passwordstr = password.value['value'];
        cpasswordstr = cpassword.value['value'];
    }

    if(namestr === '' || limitnumber === '' ||  (usingPassword.value && (passwordstr === '' || cpasswordstr === '')))
        return;
    if(passwordstr !== cpasswordstr)
        return;
    
    AsyncRequest('put', '/api/lobby', {
        'name': namestr,
        'maxplayers': parseInt(limitnumber),
        'password': usingPassword.value ? passwordstr : null
    }, (resp) => {
        if(resp.hasOwnProperty('code') && resp['code'] === 'success')
            redirect('/game');
        else//Shouldn't be reached
            console.log(resp['error']);
    });
}

function onkeyup(event) { submitted.value = false; }
</script>

<template>
<div class="modal fade" id="create-lobby" tabindex="-1" aria-labelledby="create-lobby-modal" aria-hidden="true">
    <div class="center-content">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body center">
                    <form class="form" :class="submitted ? 'was-validated' : 'needs-validation'" :novalidate="submitted ? null : true">
                        <div class="btn-modal-close">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="my-4 text-center">
                            <h3>Create lobby</h3>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Lobby name:</label>
                            <input type="text" class="form-control" id="name" ref="name" @keyup="onkeyup" placeholder="Lobby's name" required>
                            <div class="invalid-feedback" for="name">
                                Please choose your lobby's name.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="limit" class="form-label">Maximum number of players:</label>
                            <input type="number" class="form-control" id="limit" ref="limit" @keyup="onkeyup" placeholder="Number of players" min="1" max="5" required>
                            <div class="invalid-feedback" for="limit">
                                The number of players should be between 1 and 5.
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="usingPassword" v-model="usingPassword">
                            <label class="form-check-label" for="usingPassword">Use passwrod</label>
                        </div>
                        <div v-if="usingPassword" class="mb-3">
                            <input type="password" class="form-control" id="password" ref="password" @keyup="onkeyup" placeholder="Password" :required="usingPassword ? true : null">
                            <div class="invalid-feedback" for="password">
                                Please enter a password.
                            </div>
                        </div>
                        <div v-if="usingPassword" class="mb-3">
                            <input type="password" class="form-control" id="cpassword" ref="cpassword" @keyup="onkeyup" placeholder="Confirm Password" :required="usingPassword ? true : null">
                            <div class="invalid-feedback" for="cpassword">
                                Please enter a confirmation password.
                            </div>
                        </div>
                        <div class="mb-5">
                            <button type="submit" class="btn btn-block btn-primary" @click="(event) => create(event)">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>