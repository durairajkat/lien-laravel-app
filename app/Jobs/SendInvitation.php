<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInvitation
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $firstName;
    public $lastName;
    public $company;
    public $email;
    public $mes;
    public $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $firstName, $company, $lastName, $message, $url)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->company = $company;
        $this->mes = $message;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $data = ['name' => $this->firstName, 'url' => $this->url, 'mes' => $this->mes];
            Mail::send('basicUser.user.invite_email', $data, function ($m) {
                $m->subject('Invitation for Registration!');
                $m->from('admin@nlbapp.com', 'Admin');
                $m->to($this->email);
            });
            Log::info('send invitation mail to ' . $this->email);
        } catch (\Exception $exception) {
            Log::info('Mail Error : ' . $exception->getMessage());
        }
    }
}
