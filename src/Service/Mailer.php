<?php


namespace App\Service;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $template
     * @param array<mixed>|null $data
     * @return bool
     * @throws TransportExceptionInterface
     */
    public function mail(string $from, string $to, string $subject, string $template, array $data = null): bool
    {


        $mail = new TemplatedEmail();
        $mail
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template);

        if (is_array($data)) {
            $mail->context($data);
        }

        try {
            $this->mailer->send($mail);
            return true;
        } catch (TransportException $transportException) {

        }

        return false;

    }

}

