<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendLienPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $lien;
    public $admin;
    public $pdf;
    public $files;
    public $adminDetails;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lien, $admin, $pdf, $files, User $adminDetails)
    {
        if (is_array($lien)) {
            if (isset($lien[0])) {
                $this->lien = $lien[0];
            } else {
                $this->lien = 'empty@empty.com';
            }
        } else {
            $this->lien = $lien;
        }
        if (is_array($admin)) {
            if (isset($admin[0])) {
                $this->admin = $admin[0];
            } else {
                $this->admin = 'adminempty@empty.com';
            }
        } else {
            $this->admin = $admin;
        }
        $this->pdf = $pdf;
        $this->files = $files;
        $this->adminDetails = $adminDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::send('basicUser.email.lienPdf', [$this->lien, $this->admin], function ($m) {
                $m->from("info@nlb-access.com", "Job Information");
                $m->to($this->lien)->cc($this->admin)->subject('Job Information copy!');
                $m->attach($this->pdf);
                if (count($this->files) > 0) {
                    foreach ($this->files as $file) {
                        $m->attach(env('ASSET_URL') . '/upload/' . $file);
                    }
                }
            });

            Log::info('send job info to liens ' . $this->lien);

            Mail::send('basicUser.email.adminPdf', [$this->adminDetails], function ($m) {
                $m->from("info@nlb-access.com", "Job Information");
                $m->to($this->admin)->subject('Job Information copy!');
                $m->attach($this->pdf);
                if (count($this->files) > 0) {
                    foreach ($this->files as $file) {
                        $m->attach(env('ASSET_URL') . '/upload/' . $file);
                    }
                }
            });

            Log::info('send job info to admin ' . $this->admin);
        } catch (\Exception $exception) {
            Log::info('Mail Error : ' . $exception->getMessage());
        }
    }
}
