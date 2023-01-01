<?php

declare(strict_types = 1);

namespace App\Email\Contract;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

interface EmailInterface
{
    public function getTemplate(): string;

    public function getEmail(User $user): TemplatedEmail;

    public function send(User $user): void;
}
