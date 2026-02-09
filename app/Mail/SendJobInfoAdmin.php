<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendJobInfoAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $lien;
    public $admin;
    public $pdf;
    public $files;
    public $adminDetails;

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

    public function build()
    {
        $mail = $this->from('admin@nlb.com')
            ->subject('Job Information copy!')
            ->view('basicUser.email.adminPdf')
            ->with([$this->adminDetails])
            ->attach($this->pdf);
        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                $mail->attach(env('ASSET_URL') . '/upload/' . $file);
            }
        }
        return $mail;
    }
}
