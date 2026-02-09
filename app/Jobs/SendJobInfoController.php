<?php

namespace App\Jobs;

use App\User;
use Mailgun\Mailgun;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendJobInfoController
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $lien;
    public $admin;
    public $pdf;
    public $files;
    public $adminDetails;
    public $basePath;
    public $userEmail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lien, $admin, $pdf, $files, User $adminDetails, $userEmail)
    {
        // Set the values for $lien and $admin, making sure they're not empty
        if(!isset($lien)) {
            $this->lien = 'asparouh.mantchev@yourbteam.com';
        } else {
            $this->lien = implode(',' , $lien);
        }

        if(isset($userEmail)) {
            $this->userEmail = $userEmail;
        } else {
            $this->userEmail = 'asparouh.mantchev@yourbteam.com';
        }

        if (is_array($admin)) {
            if (isset($admin[0])) {
                $this->admin = $admin[0];
            } else {
                $this->admin = 'asparouh.mantchev@yourbteam.com';
            }
        } else {
            $this->admin = $admin;
        }
        $this->pdf = $pdf;
        $this->files = $files;
        $this->adminDetails = $adminDetails;
        $this->basePath = base_path()."/public/upload";

    }

    /**
     * handle() sends email to Mailgun for sending to final destinations
     * @return void
     */
    public function handle()
    {
        try {
            // Set up Mailgun
                //  to initialise the client you do:
$client = Mailgun::create(env('MAILGUN_KEY'));

// Then the rest is almost equal, except when you want to send the message:
// $client->messages()->send($domain, array('to'=> ....);
            // $client = new Mailgun();
            $attachment = [];
            array_push($attachment, array("filePath"=>$this->pdf, "filename"=>$this->pdf));
            Log::info('pdf: ' . $this->pdf);

            if (!empty($this->files)) {
                foreach ($this->files as $file) {
                    array_push($attachment, (
                        array(
                            'filePath' => $this->basePath . '/'.$file,
                            'filename' => $file
                        )
                    ));
                    Log::info('pushing: ' . $this->basePath .'/'.$file);
                }
            }

            Log::info('attachment: ');
            Log::info($attachment[0]);
            Log::info('lien : ' . $this->lien);
            Log::info('admin : ' . $this->admin);
            Log::info('userEmail : ' . $this->userEmail);

            // Create the email to send to the Lien Provider
            $resultLien = $client->messages()->send(env('MAILGUN_DOMAIN'), array(
                'from' => 'Admin <admin@nlbapp.com>',
                'to' => $this->lien,
                'cc' => $this->admin, $this->userEmail,
                'subject' => 'Job Information Sheet Copy!',
                'html' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                    <title>Job Information Sheet</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                    <style>
                        a[href], a[href]:visited{
                            color: #fff;
                        }
                        body{
                            padding:0;
                            margin:0 auto;
                            font:12pt "Helvetica";
                            color:#2d3436;
                            width:100%;
                            min-height:100vh;
                            background:#ececec;
                        }
                        header, footer{
                            background: #1084ff;
                            min-height:100px;
                            padding:0 2%;
                            width:96%;
                            color:#fff;
                        }
                        footer{
                            min-height:150px;
                            padding:2%;
                        }
                        footer address{
                            font-size:10pt;
                            font-style:normal;
                            text-align:center;
                            margin:0 auto;
                        }
                        footer address a{
                            color:#fff;
                        }
                        footer h2{
                            font-size:11pt;
                        }
                        header img{
                            display:inline-block;
                            vertical-align:middle;
                            transform:translateY(50%);
                        }
                        header h2{
                            display:inline-block;
                            width:50%;
                            float:right;
                            text-align:right;
                            transform:translateY(40%);
                        }
                        header h2 a{
                            text-decoration:none;
                            color:#fff;
                            transition-duration:1000ms;
                        }
                        header h2 a:hover{
                            color:#dfe6e9;
                        }
                        h1{
                            padding:none;
                            font-size:0;
                        }
                        section{
                            padding:2%;
                            min-height:350px;
                        }
                        @media screen and (max-width: 535px){
                            header img{
                                transform:none;
                                display:block;
                                margin:20px auto;
                            }
                            header h2{
                                display:none;
                                width:100%;
                                text-align:center;
                                float:none;
                                transform:none;
                                font-size:10pt;
                            }
                        }
                    </style>
                </head>
                <body>
                    <header>
                        <h1>National Lien Bond</h1>
                        <img src="http://nlbapp.com/images/nlb.png" alt="NLB Logo"/>
                        <h2><a href="tel:18004327799" title="National Lien Bond Phone Number">1-800-432-7799</a></h2>
                    </header>
                    <section>
                        <h2>Hello,</h2>
                        <p>You have received a Job Information Sheet. Please review the attachment for more details.</p>
                    </section>
                    <footer>
                        <address>
                        <h2>National Lien Bond</h2>
                        <a href="http://www.nlbapp.com" title="NLB Website" target="_blank">www.nlbapp.com</a>
                        <p>440 Central Avenue Highland Park, IL 60035</p>
                        <p><a href="tel:18004327799" title="National Lien Bond Phone Number"> 1-800-432-7799</a></p>
                        <p><a href="mailto:admin@nlbapp.com" title="National Lien Bond Email">admin@nlbapp.com</a>
                        <p>To stop recieving these messages please contact <a href="http://www.nlbapp.com/" target="_blank" title="NLB">National Lien Bond</a></p>
                        </address>
                    </footer>
                </body>
                </html>',
                'attachment' => $attachment
            ));
            // Log that the email was sent
            Log::info('Job Info sent to Lien Provider: ' . $this->lien);
            // Set up the email for the Admin
            $resultAdmin = $client->messages()->send(env('MAILGUN_DOMAIN'), array(
                'from' => 'Admin <admin@nlbapp.com>',
                'to' => $this->lien,
                'cc' => $this->admin, $this->userEmail,
                'subject' => 'Job Information Sheet Copy!',
                'html' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                    <title>Job Information Sheet</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                    <style>
                        a[href], a[href]:visited{
                            color: #fff !important;
                        }
                        body{
                            padding:0;
                            margin:0 auto;
                            font:12pt "Helvetica";
                            color:#2d3436;
                            width:100%;
                            min-height:100vh;
                            background:#ececec;
                        }
                        header, footer{
                            background: #1084ff;
                            min-height:100px;
                            padding:0 2%;
                            width:96%;
                            color:#fff;
                        }
                        footer{
                            min-height:150px;
                            padding:2%;
                        }
                        footer address{
                            font-size:10pt;
                            font-style:normal;
                            text-align:center;
                            margin:0 auto;
                        }
                        footer address a{
                            color:#fff;
                        }
                        footer h2{
                            font-size:11pt;
                        }
                        header img{
                            display:inline-block;
                            vertical-align:middle;
                            transform:translateY(50%);
                        }
                        header h2{
                            display:inline-block;
                            width:50%;
                            float:right;
                            text-align:right;
                            transform:translateY(40%);
                        }
                        header h2 a{
                            text-decoration:none;
                            color:#fff;
                            transition-duration:1000ms;
                        }
                        header h2 a:hover{
                            color:#dfe6e9;
                        }
                        h1{
                            padding:none;
                            font-size:0;
                        }
                        section{
                            padding:2%;
                            min-height:350px;
                        }
                        @media screen and (max-width: 535px){
                            header img{
                                transform:none;
                                display:block;
                                margin:20px auto;
                            }
                            header h2{
                                display:none;
                                width:100%;
                                text-align:center;
                                float:none;
                                transform:none;
                                font-size:10pt;
                            }
                        }
                    </style>
                </head>
                <body>
                    <header>
                        <h1>National Lien Bond</h1>
                        <img src="http://nlbapp.com/images/nlb.png" alt="NLB Logo"/>
                        <h2><a href="tel:18004327799" title="National Lien Bond Phone Number">1-800-432-7799</a></h2>
                    </header>
                    <section>
                        <h2>Hello ' . (isset($this->adminDetails) ? $this->adminDetails->name : '') . ',</h2>
                        <p>You have received a Job Information Sheet. Please review the attachment for more details.</p>
                    </section>
                    <footer>
                        <address>
                        <h2>National Lien Bond</h2>
                        <a href="http://www.nlbapp.com" title="NLB Website" target="_blank">www.nlbapp.com</a>
                        <p>440 Central Avenue Highland Park, IL 60035</p>
                        <p><a href="tel:18004327799" title="National Lien Bond Phone Number"> 1-800-432-7799</a></p>
                        <p><a href="mailto:admin@nlbapp.com" title="National Lien Bond Email">admin@nlbapp.com</a>
                        <p>To stop recieving these messages please contact <a href="http://www.nlbapp.com/" target="_blank" title="NLB">National Lien Bond</a></p>
                        </address>
                    </footer>
                </body>
                </html>',
                'attachment' => $attachment
            ));
            // Log that the email was sent
            Log::info('Job Info sent to Admin: ' . $this->admin);
            //Log::info(var_dump($resultLien));
        } catch (\Exception $exception) {
            Log::info('Mail Error : ' . $exception->getMessage());
        }
    }
}
