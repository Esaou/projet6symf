<?php


namespace App\EventSubscriber;


use App\Entity\User;
use App\Event\MailEvent;
use App\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailSubscriber implements EventSubscriberInterface
{

    private Mailer $mailer;

    private UrlGeneratorInterface $generator;

    private UriSigner $signer;

    private TranslatorInterface $translator;

    private RequestStack $requestStack;

    public function __construct(Mailer $mailer, UrlGeneratorInterface $generator, UriSigner $signer, TranslatorInterface $translator, RequestStack $requestStack) {
        $this->mailer = $mailer;
        $this->generator = $generator;
        $this->signer = $signer;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    public function sendConfirmationMail(MailEvent $event) {

        /** @var User $user */
        $user = $event->getUser();

        $url = $this->generator->generate('user_confirm',['token'=>$user->getToken()],UrlGeneratorInterface::ABSOLUTE_URL);

        $url = $this->signer->sign($url);

        $result = $this->mailer->mail(MailEvent::FROM, $user->getEmail(), MailEvent::SUBJECT, MailEvent::TEMPLATE, ['user'=>$user,'url'=>$url]);

        if (true === $result) {
            $this->requestStack->getSession()->getFlashBag()->add('success', $this->translator->trans('register.flashSuccess'));
            $event->stopPropagation();
        }

        if (false === $result) {
            $this->requestStack->getSession()->getFlashBag()->add('danger',$this->translator->trans('register.flashDanger'));
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return  [
            MailEvent::class => [
                ['sendConfirmationMail', 1]
            ]
        ];
    }
}