<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

use App\Traits\Dumper;

class VerifyApiEmail extends VerifyEmailBase implements ShouldQueue
{
    use Queueable, Dumper;	
	
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        /** Frontend URL for confirming Emails */
        $frontend_route_url = env('FRONTEND_URL') . '/verify-email/?';

        $temporarySignedURL =  URL::temporarySignedRoute(
            'verificationapi.verify', Carbon::now()->addMinutes(60), ['id' => $notifiable->getKey()]
        );

        // $this->dumpToSlack($temporarySignedURL);

        // return $frontend_route_url . 'queryURL=' . urlencode($temporarySignedURL);
        return $frontend_route_url . 'queryURL=' . $temporarySignedURL;
    }

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('Please verify your email address'))
            ->line(Lang::get('Verify your email address'))
            ->line(Lang::get('Hi! Thank you for signing up! To finish setting up your account, we just need to make sure that this account is yours.'))
            ->line(Lang::get('Please click the button below to verify your email.'))
            ->action(Lang::get('Verify Email Address'), $url);
    }
}