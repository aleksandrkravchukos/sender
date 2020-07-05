<?php

namespace App\Mail;

use App\Services\SenderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailForQueuing extends Mailable
{
    use Queueable, SerializesModels;

    private $message;

    private $subject;

    /**
     * Create a new message instance.
     *
     * @param string $message
     */
    public function __construct(string $message )
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(SenderInterface::SAMPLE_SEND_TO_EMAIL, SenderInterface::SAMPLE_SEND_TO_NAME)
            ->subject('Test email')
            ->view('emails.example_mail')
            ->with([
                'body_message' => $this->message
            ]);
    }
}
