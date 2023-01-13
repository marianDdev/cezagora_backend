<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConnectionRequestAccepted extends Notification
{
    use Queueable;

    private string $requesterOrganizationName;
    private string $authOrganizationType;
    private ?string  $authOrganizationName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        string $requesterOrganizationName,
        string $authOrganizationType,
        string $authOrganizationName = null
    )
    {

        $this->requesterOrganizationName = $requesterOrganizationName;
        $this->authOrganizationType = $authOrganizationType;
        $this->authOrganizationName = $authOrganizationName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS'))
            ->greeting(sprintf('Hello, %s', $this->requesterOrganizationName))
            ->line(sprintf('%s %s accepted your connection request.', $this->authOrganizationType, $this->authOrganizationName))
            ->action('Click here to see his Cezagora profile', url('/'))
            ->line('Thank you for using Cezagora!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
