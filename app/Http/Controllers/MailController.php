<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
    //
    public function sendMail() {
        try {
            $to = 'sujan50029@gmail.com';
            $message = "This mail is for resetting the password";
            // $reponse = Mail::to($to)->send(new SendMail($message));
            return "Mail sent successfully!";
        }
        catch(Exception $e){
            Log::error("Unable to send Mail..",[$e->getMessage()]);
        }
    }
}
