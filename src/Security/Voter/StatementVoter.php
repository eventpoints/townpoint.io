<?php

declare(strict_types = 1);

namespace App\Security\Voter;

use App\Entity\Statement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class StatementVoter extends Voter
{
    final public const VIEW = 'STATEMENT_VIEW';

    final public const CREATE = 'STATEMENT_CREATE';

    final public const EDIT = 'STATEMENT_EDIT';

    final public const DELETE = 'STATEMENT_DELETE';

    public function __construct(
        private readonly Security $security
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::DELETE], true)
            && $subject instanceof Statement || $subject === null;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (! $user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($subject, $user),
            self::EDIT => $this->canEdit($subject, $user),
            self::CREATE => $this->canCreate($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            default => false
        };
    }

    private function canView(null|Statement $statement, UserInterface $user): bool
    {
        return true;
    }

    private function canEdit(null|Statement $statement, UserInterface $user): bool
    {
        return $statement?->getOwner() === $user;
    }

    private function canCreate(null|Statement $statement, UserInterface $user): bool
    {
        return true;
    }

    private function canDelete(null|Statement $statement, UserInterface $user): bool
    {
        return $statement?->getOwner() === $this->security->getUser();
    }
}
