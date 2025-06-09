<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateAuditsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $audits;

    /**
     * Create a new event instance.
     */
    public function __construct($audits = null)
    {
        $this->audits = $audits;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('audits-updated'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'audit.updated';
    }
    
    public function broadcastWith(): array
    {
        return [
            'audits' => $this->audits,
        ];
    }
}
