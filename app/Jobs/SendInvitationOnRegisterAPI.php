<?php

namespace App\Jobs;

use Log;
use Mail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInvitationOnRegisterAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $newUser;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::send('basicUser.email.registration_credentials', [
                'newUser' => $this->newUser,
                'password' => $this->newUser->password
            ], function ($message) {
                $message->to($this->newUser->email)
                    ->subject('Welcome to Our Platform - Your Credentials');
            });
        } catch (\Exception $exception) {
            Log::info('Mail Error : ' . $exception->getMessage());
        }
    }
}
