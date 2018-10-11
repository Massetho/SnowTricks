<?php
/**
 * @description : Service for managing mailing
 * @Author : Quentin Thomasset
 */

namespace App\Service;

class Mailer
{
    /**
     * @var string $adminMail
     */
    private $adminMail;

    /**
     * @var string $adminMail
     */
    private $swiftMailer;

    //FUNCTIONS

    /**
     * Mailer constructor.
     * @param $adminMail
     * @param \Swift_Mailer $mailer
     */
    public function __construct($adminMail, \Swift_Mailer $mailer)
    {
        $this->adminMail = $adminMail;
        $this->swiftMailer = $mailer;
    }

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
        $message = (new \Swift_Message($subject))
            ->setFrom($this->adminMail)
            ->setTo(array($targetMail => $targetName))
            ->setBody($content,'text/html'
            )
        ;

        $this->swiftMailer->send($message);
    }
}
