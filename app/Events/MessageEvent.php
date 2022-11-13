<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $message;
    private string $otherOrganizationId;

    public function __construct(string $message, int $otherOrganizationId)
    {
        $this->message = $message;
        $this->otherOrganizationId = $otherOrganizationId;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat');
    }

    public function broadcastAs()
    {
        return 'message.new';
    }
}
