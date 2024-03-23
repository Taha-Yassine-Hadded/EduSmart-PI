<?php

namespace App\Service\UserService;


use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class MailSender
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $recipient, string $subject, string $content): void
    {
        $email = (new Email())
            ->from("espritedusmart@gmail.com")
            ->to($recipient)
            ->subject($subject)
            ->text($content);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            echo '<pre style="color: red;">', print_r($e, TRUE), '</pre>';
        }
    }
}
