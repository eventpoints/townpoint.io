<?php

namespace App\Service\Subscription;

use App\Exception\ShouldNotHappenException;
use App\Model\SubscriptionPlan;

class SubscriptionHelper
{
    /** @var array<SubscriptionPlan> */
    private array $plans = [];
    public function __construct()
    {
        $this->plans[] = new SubscriptionPlan(
            id:'prod_NaLXMdQ2jkFHh0',
            name:'STANDARD ANNUAL',
            price: 4
        );
        $this->plans[] = new SubscriptionPlan(
            id:'prod_NBzCZSjz7jiFQi',
            name:'STANDARD MONTHLY',
            price: 40
        );
    }

    /**
     * @throws ShouldNotHappenException
     */
    public function findPlan(string $id) : SubscriptionPlan
    {
        foreach ($this->plans as $plan) {
            if ($plan->getId() == $id) {
                return $plan;
            }
        }

        throw new ShouldNotHappenException('plan id not found');
    }
}