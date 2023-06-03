<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConnectionRequestAccepted extends Notification
{
    use Queueable;

    private string $requesterCompanyName;
    private string $authCompanyType;
    private ?string  $authCompanyName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        string $requesterCompanyName,
        string $authCompanyType,
        string $authCompanyName = null
    )
    {

        $this->requesterCompanyName = $requesterCompanyName;
        $this->authCompanyType = $authCompanyType;
        $this->authCompanyName = $authCompanyName;
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
            ->greeting(sprintf('Hello, %s', $this->requesterCompanyName))
            ->line(sprintf('%s %s accepted your connection request.', $this->authCompanyType, $this->authCompanyName))
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
