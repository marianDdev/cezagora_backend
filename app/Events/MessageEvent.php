<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    private $interlocutor_id;
    private $message;

    public function __construct($interlocutor_id, $message)
    {
        $this->message         = $message;
        $this->interlocutor_id = $interlocutor_id;
    }

    public function broadcastWith()
    {
        return [
            'id'         => Str::orderedUuid(),
            'author_id'  => Auth::user()->id,
            'message'    => $this->message,
            'created_at' => now()->toDateTimeString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.new';
    }

    public function broadcastOn(): Channel
    {
        return new Channel('public.room');
    }
}
