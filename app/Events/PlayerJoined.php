<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerJoined implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id;
    public string $nickname;
    public int $players;

    /**
     * Create a new event instance.
     * @param int $id Game's id
     * @param string $nickname User's nickname
     * @param int $players New number of current players in room
     * @return void
     */
    public function __construct(int $id, string $nickname, int $players)
    {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->players = $players;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() { return new Channel('room_' . $this->id); }

}
