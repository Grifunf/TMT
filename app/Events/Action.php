<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Action implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id;
    public int $pid;
    public string $action;
    public string $resource;
    public int $ammount;

    /**
     * Create a new event instance.
     * @param int $id Game's id
     * @param string $name Game's name
     * @param int $maxplayers Maximum number of allowed players
     * @return void
     */
    public function __construct(int $id, int $pid, string $action, string $resource, int $ammount)
    {
        $this->id = $id;
        $this->pid = $pid;
        $this->action = $action;
        $this->resource = $resource;
        $this->ammount = $ammount;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() { return new Channel('room_' . $this->id); }

}