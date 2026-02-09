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

class SendInvitationOnRegister implements ShouldQueue
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
            $superUsers = User::where('role', 1)->get();
            foreach ($superUsers as $superUser) {
                Mail::send('basicUser.user.registration_email', ['superUser' => $superUser, 'newUser' => $this->newUser], function ($message) use ($superUser) {
                    // $message->from(env('MAIL_FROM'), $subject);
                    $message->to($superUser->email)->subject('New User Registered');
                });

//                Log::info('send registration mail to ' . $superUser->email);
            }
        } catch (\Exception $exception) {
//            Log::info('Mail Error : ' . $exception->getMessage());
        }
    }
}
