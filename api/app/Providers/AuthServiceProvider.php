<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Inside your `boot()` function
        VerifyEmail::createUrlUsing(function ($notifiable) {

            // collect and sort url params
            $params = [
                'expires' => \Carbon\Carbon::now()
                    ->addMinutes(\Config::get('auth.verification.expire', 60))
                    ->getTimestamp(),
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ];
    
            ksort($params);
    
            // then create API url for verification. my API have `/api` prefix,
            // so i don't want to show that url to users 
            $url = \URL::route(
                'verification.verify',
                $params,
                true
            );
    
            // get APP_KEY from config and create signature
            $key = config('app.key');
            $signature = hash_hmac('sha256', $url, $key);
    
            // generate url for yous SPA page to send it to user
            return env('SPA_URL') . '/verify' . '?' . http_build_query($params + compact('signature'), false);
    
            });
    
            ResetPassword::createUrlUsing(function ($user, string $token) {
                return env('SPA_URL') . '/reset?token='.$token;
            });

    }
}
