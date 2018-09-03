<?php
/**
 * @description : Service for managing mailing
 * @Author : Quentin Thomasset
 */

namespace App\Service;

use \SendGrid;

class Mailer
{
    /**
     * @var string $sendgridKey
     */
    private $sendgridKey;

    /**
     * @var string $adminMail
     */
    private $adminMail;

    //FUNCTIONS

    /**
     * @param string $sendgridKey
     * @param string $adminMail
     */
    public function __construct($sendgridKey, $adminMail)
    {
        $this->sendgridKey = $sendgridKey;
        $this->adminMail = $adminMail;
    }

    //GETTERS & SETTERS

    /**
     * @param string $subject
     * @param string $content
     * @param string $targetName
     * @param string $targetMail
     */
    public function sendMail(
        $subject,
                             $content,
                             $targetName,
                             $targetMail
    ) {
        $from = new SendGrid\Email("Blogpro", $this->adminMail);
        $to = $targetName;
        $to = new SendGrid\Email($to, $targetMail);

        $content = new SendGrid\Content("text/html", $content);
        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        $sg = new \SendGrid($this->sendgridKey);
        $sg->client->mail()->send()->post($mail);
    }
}
