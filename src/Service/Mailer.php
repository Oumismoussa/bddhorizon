<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer {
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
     
    }

    public function sendEmail($email, $token)
    {
       
        $email = (new TemplatedEmail())
        ->from(addresses: 'registration@example.com')
        ->to(new Address($email))
        ->subject(subject: 'Time for Symfony Mailer!')
        ->htmlTemplate(template:'emails/registration.html.twig')
        ->context([
            'token'=>$token,
        ]);
        $this->mailer->send($email);
}
}