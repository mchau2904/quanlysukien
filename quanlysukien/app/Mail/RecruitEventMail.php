<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecruitEventMail extends Mailable
{
    use Queueable, SerializesModels;
    public Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject('📢 Huy động tham gia sự kiện: ' . $this->event->event_name)
            ->view('emails.recruit')
            ->with(['event' => $this->event]);
    }
}
