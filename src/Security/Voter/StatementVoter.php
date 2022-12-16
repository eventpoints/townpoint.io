<?php

namespace App\Security\Voter;

use App\Entity\Statement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class StatementVoter extends Voter
{


    public const VIEW = 'STATEMENT_VIEW';
    public const CREATE = 'STATEMENT_CREATE';
    public const EDIT = 'STATEMENT_EDIT';
    public const DELETE = 'STATEMENT_DELETE';

    public function __construct(
        private readonly Security $security
    )
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::DELETE])
            && $subject instanceof Statement || $subject == null;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
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
        return $statement->getOwner() === $user;
    }

    private function canCreate(null|Statement $statement, UserInterface $user): bool
    {
        return true;
    }

    private function canDelete(null|Statement $statement, UserInterface $user): bool
    {
        return $statement->getOwner() === $this->security->getUser();
    }
}
