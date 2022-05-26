<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Game;
use App\Models\History;

use App\Events\GameAdded;
use App\Events\PlayerJoined;
use App\Events\GameUpdate;
use App\Events\Action;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * Class for handling player logic on our app
 */
class GameService
{
    /**
     * Add a new game room into the database
     * @param int $pid Player's id that is creating the game
     * @param string $name user specified name for the game room
     * @param int $maxplayer Specified limit to the number of players
     * @param string $password User specified password (aka "PIN")
     * @return int the id of the newly created game
     */
    public function CreateGame(int $pid, string $name, int $maxplayers, string $password = null): int
    {
        $players = Player::where('id', '=', $pid);
        if($players->count() != 1)
            return 0;
        
        //Create game
        $game = new Game;
        $game->name = $name;
        $game->maxplayers = $maxplayers;
        if($password != null)
        {
            $game->salt = Str::random(16);
            $secret = substr($game->salt, 0, 8) . $password . substr($game->salt, 8, 8);
            $game->password = hash('sha512', $secret);
        }
        else
        {
            $game->salt = '';
            $game->password = '';
        }
        $game->save();
        
        //Update player's gid
        $player = $players->first();
        $player->gid = $game->id;
        $player->save();
        //Broadcast event
        GameAdded::dispatch($game->id, $name, $maxplayers, $game->salt !== '');
        return $game->id;
    }

    /**
     * Add a player into the specified game room
     * @param int $id Game's id
     * @param int $pid Player's id
     * @param string $password Given password to be checked for authetication
     * @return bool True if player manage to join game successfully, false otherwise
     */
    public function JoinGame(int $id, int $pid, string $password = null): bool
    {
        //Check if user can join & update database
        $games = Game::where('id', '=', $id)->where('id', '!=', 0);
        $players = Player::where('id', '=', $pid);
        if($games->count() != 1 || $players->count() != 1)
            return false;
        $game = $games->first();
        if($game->currplayers===$game->maxplayers || ($game->salt != '' && $password===null))
            return false;
        if($password != null && $game->salt != '')
        {
            $secret = substr($game->salt, 0, 8) . $password . substr($game->salt, 8, 8);
            if(hash('sha512', $secret) != $game->password)
                return false;
        }
        $game->currplayers += 1;
        $game->save();

        //Update player info
        Player::where('id', '=', $pid)
            ->update([
                'gid' => $id,
                'tr' => 20,
                'gold'=>0,
                'goldprod' => 1,
                'plant' => 0,
                'plantprod' => 1,
                'steel' => 0, 
                'steelprod' => 1,
                'titan' =>  0,
                'titanprod' => 1,
                'energy' =>  0,
                'energyprod' => 1,
                'heat' =>  0,
                'heatprod' => 1
            ]);
        
        //Broadcast events
        $player = $players->first();
        PlayerJoined::dispatch($id, $player->name, $game->currplayers);//In room event
        GameUpdate::dispatch($id, $game->currplayers);//Lobby event
        return true;
    }

    /**
     * Get the list of games that are currently running
     *  (for the time being I'm returning the whole list since we are only testing)
     * @return Collection The games that are currently running
     */
    public function GetGames()
    {
        return Game::select('id', 'name', 'currplayers', 'maxplayers', DB::raw("(salt != '') AS password"))
            ->where('id', '!=', 0)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all in-game information about
     * @return array
     */
    public function GetInfo(int $id): array
    {
        $games = Game::where('id', '=', $id)->where('id', '!=', 0);
        if($games->count() != 1)
            return array('error' => "Couldn't find the specified game.");
        $generation = $games->first()->gen;

        $players = Player::where('gid', '=', $id)->orderBy('name')->get();
        return array(
            'gen' => $generation,
            'players' => $players
        );
    }

    /**
     * Does a player in game action
     * @param int $id Game's id 
     * @param int $pid Player's id
     * @param string $action "SQL Enumaration" defining player's action
     * @param string $resource "SQL Enumaration" defining the resource that the player is changing
     * @param int $ammount Number of resources that change
     * @return bool True if player could make the action, false otherwise
     */
    public function MakeAction(int $id, int $pid, string $action, string $resource = null, int $ammount = 0): bool
    {
        $games = Game::where('id', '=', $id)->where('id', '!=', 0);
        $players = Player::where('id', '=', $pid);
        if($games->count() != 1 || $players->count() != 1)
            return false;
        
        $player = $players->first();
        $flag = false;
        switch($action)
        {
            case 'GOLD_GREENERY':
                $flag = GreeneryWithGold($player);
                break; 
            case 'CITY':
                $flag = City($player);
                break;
            case 'OCEAN':
                $flag = Ocean($player);
                break;
            case 'ENERGY_PROD':
                $flag = EnergyProduction($player);
                break;
            case 'GOLD_TEMP':
                $flag = TemperatureWithGold($player);
                break;
            case 'HEAT_TEMP':
                $flag = TemperatureWithHeat($player);
                break;
            case 'LEAFS_GREENERY':
                $flag = GreeneryWithLeafs($player);
                break;
            case 'ADD':
            case 'REMOVE':
                if($resource === null || $ammount === 0)
                    $flag = false;
                else
                    $flag = SimpleAction($player, $resource, $ammount);
                break;
            case 'PRODUCE':
                $flag = Produce($player);
                break;
            default://Unknown action
                return false;
        }

        if(!$flag)
            return false;

        //Broadcast message
        Action::dispatch($id, $pid, $action , $resource, $ammount);
        return true;
    }

    /**
     * Standard greenery project using gold to pay
     * @param Player $player The player that is making the action
     * @return bool True if player meets the requirements for action, false otherwise
     */
    private function GreeneryWithGold(Player $player): bool
    {
        if($player->gold < 23)
            return false;
        $player->gold -= 23;
        $player->save(); 
        return true;
    }

    /**
     * Standard city project using gold to pay
     * @param Player $player The player that is making the action
     * @return bool True if player meets the requirements for action, false otherwise
     */
    private function City(Player $player): bool
    {
        if($player->gold < 25)
            return false;
        $player->gold -= 25;
        $player->goldprod += 1;
        $player->save(); 
        return true;
    }

    /**
     * Standard ocean project using gold to pay
     * @param Player $player The player that is making the action
     * @return bool True if player meets the requirements for action, false otherwise
     */
    private function Ocean(Player $player): bool
    {
        if($player->gold < 18)
            return false;
        $player->gold -= 18;
        $player->save(); 
        return true;
    }

    /**
     * Standard energy production project using gold to pay
     * @param Player $player The player that is making the action
     * @return bool True if player meets the requirements for action, false otherwise
     */
    private function EnergyProduction(Player $player): bool
    {
        if($player->gold < 11)
            return false;
        $player->gold -= 11;
        $player->energyprod += 1;
        $player->save(); 
        return true;
    }

    /**
     * Standard temperature project using gold to pay
     * @param Player $player The player that is making the action
     * @return bool True if player meets the requirements for action, false otherwise
     */
    private function TemperatureWithGold(Player $player): bool
    {
        if($player->gold < 14)
            return false;
        $player->gold -= 14;
        $player->save(); 
        return true;
    }

    /**
     * Standard temperature project using heat to pay
     * @param Player $player The player that is making the action
     * @return bool True if player meets the requirements for action, false otherwise
     */
    private function TemperatureWithHeat(Player $player): bool
    {
        if($player->heat < 8)
            return false;
        $player->heat -= 8;
        $player->save(); 
        return true;
    }

    /**
     * Standard greenery project using leafs to pay
     * @param Player $player The player that is making the action
     * @return bool True if player meets the requirements for action, false otherwise
     */
    private function GreeneryWithLeafs(Player $player): bool
    {
        if($player->plant < 8)
            return false;
        $player->plant -= 8;
        $player->save(); 
        return true;
    }

    /**
     * User action that changes the his "inventory"
     * @param Player $player The players that is making the action
     * @param string $resource The resource type the the player is changing
     * @param int $ammount The ammout of resources that will change (negative number for removal)
     */
    private function SimpleAction(Player $player, string $resource, int $ammount)
    {
        if(!property_exists($player, $resource))
            return false;
        if($player->$resource + $ammount < 0)
            return false;
        $player->$resource += $ammount;
        return true;
    }

    //Not implemented yet
    private function Produce() { return false; }


}