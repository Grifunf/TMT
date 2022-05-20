<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the history records for a player (One to Many Relationship between Player and History)
     * @return HasMany Relationship between Player and History
     */
    public function actions() { return $this->hasMany(History::class, 'pid'); }

    /**
     * Gets the game that the player is currently playing
     * @return Game A game record
     */
    public function game() { return $this->belongsTo(Game::class, 'gid'); }
}