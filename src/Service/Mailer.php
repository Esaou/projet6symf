<?php


namespace App\Service;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function mail(string $from,string $to,string $subject,string $template,array $data = null) {

        $mail = new TemplatedEmail();
        $mail
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($data);

        try {
            $this->mailer->send($mail);
            return true;
        } catch (TransportException $transportException) {

        }

        return false;

    }

}