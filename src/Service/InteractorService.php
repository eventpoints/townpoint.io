<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\View;
use App\Repository\InteractorRepository;
use App\Repository\UserRepository;
use App\Repository\ViewRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;

class InteractorService
{

    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function check(User $currentUser, User $targetUser) : void
    {
        dd($this->userRepository->createAvgUserViewsCountCriteria($currentUser));
        $this->hasMutualProfileViews($currentUser, $targetUser);
    }

    private function hasMutualProfileViews(User $currentUser, User $targetUser) : void
    {
        if($currentUser === $targetUser){
            return;
        }

         $currentUserViewsOnTargetUser = $currentUser->getViewed()->matching(
            ViewRepository::createViewsByUserCriteria($targetUser)
        );
         
        $currentUserFirstView = $currentUserViewsOnTargetUser->first();
        $currentUserLastView = $currentUserViewsOnTargetUser->last();
        $percentageOfCurrentUserViews = ($currentUserViewsOnTargetUser->count() / $currentUser->getViewed()->count()) * 100;

         $targetUserViewsOnCurrentUser = $targetUser->getViewed()->matching(
             ViewRepository::createViewsByUserCriteria($currentUser)
        );
        $targetUserFirstView = $targetUserViewsOnCurrentUser->first();
        $targetUserLastView = $targetUserViewsOnCurrentUser->last();
        $percentageOfTargetUserViews = ($targetUserViewsOnCurrentUser->count() / $targetUser->getViewed()->count()) * 100;

        dd($percentageOfCurrentUserViews, $percentageOfTargetUserViews);
    }

    private function isMutuallyMessaging(User $owner, User $user) : bool
    {}


}