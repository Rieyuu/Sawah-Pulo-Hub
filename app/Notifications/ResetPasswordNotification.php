<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordParent;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPasswordParent
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Atur Ulang Kata Sandi - Sawah Pulo Farm')
            ->greeting('Halo!')
            ->line('Anda menerima email ini karena kami menerima permintaan atur ulang kata sandi untuk akun Anda di Sawah Pulo Farm.')
            ->action('Atur Ulang Kata Sandi', $url)
            ->line('Tautan atur ulang kata sandi ini akan kedaluwarsa dalam ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' menit.')
            ->line('Jika Anda tidak merasa mengajukan permintaan ini, silakan abaikan email ini.')
            ->salutation('Salam hangat, Pengelola Sawah Pulo Farm');
    }
}
