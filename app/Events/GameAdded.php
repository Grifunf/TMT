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

    public int $id;
    public string $name;
    public int $maxplayers;
    public bool $haspass;

    /**
     * Create a new event instance.
     * @param int $id Game's id
     * @param string $name Game's name
     * @param int $maxplayers Maximum number of allowed players
     * @return void
     */
    public function __construct(int $id, string $name, int $maxplayers, bool $haspass)
    {
        $this->id = $id;
        $this->name = $name;
        $this->maxplayers = $maxplayers;
        $this->haspass = $haspass;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() { return new Channel('lobby'); }

}
