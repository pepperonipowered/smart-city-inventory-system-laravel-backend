<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardEvents implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $dashboardData;

    /**
     * Create a new event instance.
     */
    public function __construct($dashboardData = null)
    {
        $this->dashboardData = $dashboardData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard-updates'),
        ];
    }

    public function broadcastAs()
    {
        return 'dashboard.updated';
    }

    public function broadcastWith()
    {
        return [
            'dashboardData' => $this->dashboardData,
        ];
    }
}
