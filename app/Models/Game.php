<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Gets the players on the current game (One to Many Relationship between Game and Player)
     * @return HasMany Relationship between Game and Player
     */
    public function players() { return $this->hasMany(Player::class, 'gid'); }

    /**
     * Gets the history records for the current game (One to Many Relationship between Game and History)
     * @return HasMany Relationship between Game and History
     */
    public function actions() { return $this->hasMany(History::class, 'gid'); }
    
}