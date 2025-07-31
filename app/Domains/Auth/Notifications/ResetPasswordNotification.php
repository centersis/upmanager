<?php

namespace App\Domains\Auth\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends ResetPassword
{
    use Queueable;

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);
        $count = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject(__('passwords.reset_password_notification.subject'))
            ->greeting(__('passwords.reset_password_notification.greeting'))
            ->line(__('passwords.reset_password_notification.line_1'))
            ->action(__('passwords.reset_password_notification.action'), $url)
            ->line(__('passwords.reset_password_notification.line_2', ['count' => $count]))
            ->line(__('passwords.reset_password_notification.line_3'))
            ->salutation(__('passwords.reset_password_notification.salutation', ['app_name' => config('app.name')]));
    }
} 