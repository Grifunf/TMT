<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PlayerService;


class PlayerController extends Controller
{
    protected PlayerService $service;

    public function __construct() { $this->service = new PlayerService; }

    /**
     * Responds to put request with the id of the new players
     * @param Illuminate\Http\Request $req Object containing the http request that made to the server
     * @return array json object containing the result payload
     */
    public function CreatePlayer(Request $req)
    {
        if(!$req->filled('nickname'))
            return array('error' => 'Nickname must be specified.');
        $nickname = $req->input('nickname');
        $length = strlen($nickname);
        if($length === 0 || $length > 64)
            return array('error' => 'Nickname must be between 1 and 64 characters');
        
        $id = $this->service->CreatePlayer($nickname);
        return array('id' => $id);
    }
}