<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

final readonly class HandleService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function generate(User $user): string
    {
        $prefix = $user->getFirstName()[0] . $user->getLastName()[0];
        $handle = uniqid(prefix: $prefix);

        if (strlen($handle) > 8) {
            $handle = substr($handle, 0, 8);
        }

        if (strlen($handle) < 8) {
            $handle = str_pad($handle, 8, '0');
        }

        while ($this->userRepository->findOneBy([
            'handle' => $handle,
        ])) {
            $handle = uniqid(prefix: $prefix);

            if (strlen($handle) > 8) {
                $handle = substr($handle, 0, 8);
            }

            if (strlen($handle) < 8) {
                $handle = str_pad($handle, 8, '0');
            }
        }

        return $handle;
    }
}
