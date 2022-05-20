<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GameService;


class GameController extends Controller
{
    protected GameService $service;

    //Default contructor
    public function __construct() { $this->service = new GameService; }

    /**
     * Responds to get request with the list of games currently running
     * @param Illuminate\Http\Request $req Object containing the http request that made to the server
     * @return Collection json object containing the result payload
     */
    public function ListGames(Request $req) { return $this->service->GetGames(); }

    /**
     * Responds to get request with the in-game info
     * @param Illuminate\Http\Request $req Object containing the http request that made to the server
     * @return array json object containing the result payload
     */
    public function GameInfo(Request $req): array
    {
        if(!$req->filled('id'))
            return array('error' => 'Parameter id must be specified.');
        $id = intval($req->input('id'));    
        if($id === 0)
            return array('error' => 'Not accepted id.');
        return $this->service->GetInfo($id);
    }

    /**
     * Adds a new game to the list of active games
     * @param Illuminate\Http\Request $req Object containing the http request that made to the server
     * @return array json object containing the result payload
     */
    public function CreateGame(Request $req): array
    {
        if(!$req->filled('id') || !$req->filled('name') || !$req->filled('maxplayers'))
            return array('error' => "Name, Player's id and maxplayers must be specified.");
        
        $pid = intval($req->input('id'));
        $name = $req->input('name');
        $maxplayers = $req->input('maxplayers');
        $password = null;
        if($req->filled('password'))
            $password = $req->input('password');
        
        $id = $this->service->CreateGame($pid, $name, $maxplayers, $password);
        if($id === 0)
            return array('error' => "Couldn't find user with the specified id.");
        return array('id' => $id);
    }

    /**
     * Attempts to make player to join a game room
     * @param Illuminate\Http\Request $req Object containing the http request that made to the server
     * @return array json object containing the result payload
     */
    public function JoinGame(Request $req): array
    {
        if(!$req->filled('gid') || !$req->filled('pid'))
            return array('error' => 'Game and Player id must be specified.');
        
        $gid = intval($req->input('gid'));
        $pid = intval($req->input('pid'));
        $password = null;
        if($req->filled('password'))
            $password = $req->input('password');
        
        $flag = $this->service->JoinGame($gid, $pid, $password);
        if(!$flag)
            return array('error' => 'Failed to join the specified game.');
        return array('code' => 'Success');
    }

    /**
     * Attempts to make in-game in action for player
     * @param Illuminate\Http\Request $req Object containing the http request that made to the server
     * @return array json object containing the result payload
     */
    public function MakeAction(Request $req): array
    {
        if(!$req->filled('gid') || !$req->filled('pid') || !$req->filled('action'))
            return array('error' => 'Game and Player id as well as the action must be specified.');
        
        $gid = intval($req->input('gid'));
        $pid = intval($req->input('pid'));
        $action = $req->input('action');
        $resource = null;
        $ammount = 0;
        if($req->filled('resource') && $req->filled('ammount'))
        {
            $resource = $req->input('resource');
            $ammount = $req->input('ammount');
        }

        $flag = $this->service->MakeAction($gid, $pid, $action, $resource, $ammount);
        if(!$flag)
            return array('error' => "Failed to make action.");
        return array('code' => 'Success');
    }
}