<?php

namespace common\helpers;

use common\models\MailQueue;
use common\helpers\phpmailer\PHPMailer;
class MailSub
{
    public $isSMTP = true;
    public $Host = 'smtp.mail.ru';
    public $SMTPAuth = true;
    public $Username = 'noreply@bilimal.kz';
    public $Password = 'CWEqcd93';
    public $AuthType = 'PLAIN';
    public $Port = '587';
    public $SMTPSecure = 'tls';
    public $CharSet = 'UTF-8';
    public $From = 'noreply@bilimal.kz';
    public $FromName = 'КТЖ';
    public $addAddress;
    public $WordWrap = 50;
    public $isHTML = true;
    public $Subject;
    public $Body;
    public $AltBody;
    public $ErrorInfo;


    public function send($onQueue = true)
    {


        if ($onQueue) {

            $queue = new MailQueue();
            $queue->to = $this->addAddress;
            $queue->subject = $this->Subject;
            $queue->body = $this->Body;
            $queue->alt_body = $this->AltBody;
            $queue->ts = time();
            $queue->state = 0;
            $queue->sent = 0;
            $queue->tries = 0;
            if ($queue->save()) {
                return true;
            } else {
                $this->ErrorInfo = $queue->getErrors();
                return false;
            }

        } else {
            $mail = new PHPMailer;
            // Была ошибка отправки, потому что бралось имя сервера, а она состояла из русских букв,
            // smtp error code 501 syntactically invalid ehlo argument(s)
            $mail->Hostname = 'localhost';
            if ($this->isSMTP) $mail->isSMTP(); // Set mailer to use SMTP
            // $mail->SMTPDebug = 1;
            $mail->Host = $this->Host;
            $mail->SMTPAuth = $this->SMTPAuth;                               // Enable SMTP authentication
            $mail->SMTPSecure = $this->SMTPSecure;
            $mail->Username = $this->Username;                               // SMTP username
            $mail->Password = $this->Password;                                  // SMTP password
            $mail->AuthType = $this->AuthType;
            $mail->CharSet = $this->CharSet;
            $mail->Port = $this->Port;                                    // TCP port to connect to
            $mail->From = $this->From;
            $mail->FromName = $this->FromName;
            //$mail->addAddress('candyman99@mail.ru', 'Rafa');     // Add a recipient
            //$mail->addAddress('kamil_albatyrov@mail.ru', 'Kamil');               // Name is optional
            $mail->addAddress($this->addAddress);
            $mail->WordWrap = $this->WordWrap;                                 // Set word wrap to 50 characters
            $mail->isHTML($this->isHTML);                                  // Set email format to HTML
            $mail->Subject = $this->Subject;
            $mail->Body = $this->Body;
            $mail->AltBody = $this->AltBody;

            if (!$mail->send()) {
                $this->ErrorInfo = $mail->ErrorInfo;
                return false;

            } else {
                return true;
            }

        }


    }


}

