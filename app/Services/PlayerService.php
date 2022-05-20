<?php

namespace App\Services;

use App\Models\Player;

/**
 * Class for handling player logic on our app
 */
class PlayerService
{
    /**
     * Add a new player into the database
     * @param string $nickname user specified nickname
     * @return int User's id
     */
    public function CreatePlayer(string $nickname): int
    {
        $player = new Player;
        $player->name = $nickname;
        $player->gid = 0;
        $player->save();

        return $player->id;
    }

}