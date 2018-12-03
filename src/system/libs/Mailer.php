<?php
namespace yellowheroes\projectname\system\libs;

/**
 * Use class Mailer to send e-mails - only works in PRODUCTION - our local host does not have SMTP ini set correctly
 * 
 * Alternatively, use PHPMailer/PHPMailer (https://github.com/PHPMailer/PHPMailer/blob/_master/examples/gmail.phps)
 */

class Mailer
{
    public $from = "admin@yellowheroes.com";
    public $headers = [];
    
    public function __construct()
    {
    }
	
    /**
     * 
     * @param string $to        Receiver, or receivers of the mail. 
     * @param string $subject   Subject of the email to be sent. 
     * @param string $msg
     * @param arry $headers     When sending mail, the mail must contain a From header.
     *                          If an array is passed (PHP >7.2.0), its keys are the header names and its values are the respective header values. 
     */
    public function send($to = null, $subject = null, $msg = null, $headers = [])
    {
        $headers = "FROM:" . $this->from; // we cannot work with array, only starting from PHP >7.2.0
        $subject = $subject ?? 'no subject';         // return first operand ($subject) if it exists and is not NULL
        $msg = $msg ?? 'no message was set by the sender of this e-mail';
        \mail($to, $subject, $msg, $headers);
    }  
}
