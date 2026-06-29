<?php

namespace App\Services;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPwdMail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;

class EmailServices
{
    public const MESSAGE_SENDING_MAIL_INFO = 'Sending mail - mail service ';

    

    /**
     * @param $params The params data containing user name, email, token, url
     * @return boolean Returns true if email was send successfully, otherwise false
     */
    public function sendForgotPasswordMail($params)
    {
        
        Mail::to($params['email'])->send(new ForgotPwdMail($params));
        return true;
    }

}
