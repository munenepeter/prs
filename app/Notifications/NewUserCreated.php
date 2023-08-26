<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $temporary_password
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return $this->buildMessage(
            url: route('login.first.time.create', [
                'email' => $this->email,
            ]),
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [//
        ];
    }

    protected function buildMessage($url)
    {
        $fullname = $this->firstname . ' ' . $this->lastname;

        return (new MailMessage())->subject(Lang::get('New User Created'))->greeting("Hello $fullname")->line(
            Lang::get(
                'You are receiving this email because an account using your email was created on our platform.',
            ),
        )->line(Lang::get("Your temporary password is {$this->temporary_password}"))->line(
            Lang::get('Upon login you will need to change your temporary password to continue further.'),
        )->action(Lang::get('Login'), $url)->line(
            Lang::get(
                'If you are not part of our organisation, or you are not th intended user, you can easily delete this and no action is required further.
			.',
            ),
        );
    }
}
