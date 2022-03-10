<?php


namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class MailEvent extends Event
{
    public const TEMPLATE = 'email/confirm.html.twig';

    public const FROM = 'contact@snowtricks.com';

    public const SUBJECT = 'Confirmation de compte';

    private User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}