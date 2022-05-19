<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $name;
    public $maxplayers;

    /**
     * Create a new event instance.
     * @param int $id Game's id
     * @param string $name Game's name
     * @param int $maxplayers Maximum number of allowed players
     * @return void
     */
    public function __construct(int $id, string $name, int $maxplayers)
    {
        $this->id = $id;
        $this->name = $name;
        $this->maxplayers = $maxplayers;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() { return new Channel('lobby'); }

}
