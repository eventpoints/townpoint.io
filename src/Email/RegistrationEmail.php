<?php

declare(strict_types = 1);

namespace App\Email;

use App\Email\Contract\EmailInterface;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationEmail implements EmailInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MailerInterface $mailer
    ) {
    }

    public function getTemplate(): string
    {
        return '/email/registration.email.html.twig';
    }

    public function getEmail(User $user): TemplatedEmail
    {
        $template = new TemplatedEmail();
        $template->to(new Address($user->getEmail()));
        $template->subject($this->translator->trans('email.subject.registration'));
        $template->htmlTemplate($this->getTemplate());
        $template->context([
            'user' => $user,
        ]);

        return $template;
    }

    public function send(User $user): void
    {
        $email = $this->getEmail($user);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }
}
