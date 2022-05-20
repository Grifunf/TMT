<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'history';

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Gets the player that the record is owned from
     * @return Player A player record
     */
    public function player() { return $this->belongsTo(Player::class, 'pid'); }

    /**
     * Gets the game that for this record 
     */
    public function game() { return $this->belongsTo(Game::class, 'gid'); }
}