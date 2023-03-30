<?php

declare(strict_types=1);

use App\Entity\Auction\Item;
use App\Entity\User;
use App\Enum\AuctionWorkflowEnum;
use App\Enum\RegistrationWorkflowEnum;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('framework', [
        'workflows' => [
            'auction_publishing' => [
                'type' => 'workflow',
                'audit_trail' => [
                    'enabled' => true,
                ],
                'marking_store' => [
                    'type' => 'method',
                    'property' => 'status',
                ],
                'supports' => [Item::class],
                'initial_marking' => 'draft',
                'places' => ['draft', 'reviewed', 'rejected', 'published'],
                'transitions' => [
                    'to_review' => [
                        'from' => 'draft',
                        'to' => 'reviewed',
                    ],
                    'publish' => [
                        'from' => 'reviewed',
                        'to' => 'published',
                    ],
                    'reject' => [
                        'from' => 'reviewed',
                        'to' => 'rejected',
                    ],
                ],
            ],
            'registration' => [
                'type' => 'state_machine',
                'audit_trail' => [
                    'enabled' => true,
                ],
                'marking_store' => [
                    'type' => 'method',
                    'property' => 'state',
                ],
                'supports' => [User::class],
                'initial_marking' => RegistrationWorkflowEnum::STATE_PERSONAL_INFO->value,
                'places' => [
                    RegistrationWorkflowEnum::STATE_PERSONAL_INFO->value,
                    RegistrationWorkflowEnum::STATE_PAYMENT_INFO->value,
                    RegistrationWorkflowEnum::STATE_COMPLETE->value,
                ],
                'transitions' => [
                    RegistrationWorkflowEnum::TRANSITION_TO_PAYMENT_FORM->value => [
                        'from' => RegistrationWorkflowEnum::STATE_PERSONAL_INFO->value,
                        'to' => RegistrationWorkflowEnum::STATE_PAYMENT_INFO->value,
                    ],
                    RegistrationWorkflowEnum::TRANSITION_TO_COMPLETE->value => [
                        'from' => RegistrationWorkflowEnum::STATE_PAYMENT_INFO->value,
                        'to' => RegistrationWorkflowEnum::STATE_COMPLETE->value,
                    ],
                ],
            ],
            'auction' => [
                'type' => 'state_machine',
                'audit_trail' => [
                    'enabled' => true,
                ],
                'marking_store' => [
                    'type' => 'method',
                    'property' => 'state',
                ],
                'supports' => [Item::class],
                'initial_marking' => AuctionWorkflowEnum::STATE_DRAFT->value,
                'places' => [
                    AuctionWorkflowEnum::STATE_DRAFT->value,
                    AuctionWorkflowEnum::STATE_PENDING_MOD_REVIEW->value,
                    AuctionWorkflowEnum::STATE_MOD_REVIEW_REJECTED->value,
                    AuctionWorkflowEnum::STATE_ACCEPTING_OFFERS->value,
                    AuctionWorkflowEnum::STATE_PENDING_OFFER_ACCEPTANCE->value,
                    AuctionWorkflowEnum::STATE_PENDING_BUYER_INSPECTION->value,
                    AuctionWorkflowEnum::STATE_BUYER_INSPECTION_REJECTED->value,
                    AuctionWorkflowEnum::STATE_COMPLETE->value
                ],
                'transitions' => [
                    AuctionWorkflowEnum::TRANSITION_TO_MOD_REVIEW->value => [
                        'from' => AuctionWorkflowEnum::STATE_DRAFT->value,
                        'to' => AuctionWorkflowEnum::STATE_PENDING_MOD_REVIEW->value,
                    ],
                    AuctionWorkflowEnum::TRANSITION_TO_MOD_REVIEW_REJECTED->value => [
                        'from' => AuctionWorkflowEnum::STATE_DRAFT->value,
                        'to' => AuctionWorkflowEnum::STATE_MOD_REVIEW_REJECTED->value,
                    ],
                    AuctionWorkflowEnum::TRANSITION_TO_ACCEPTING_OFFERS_AFTER_MOD_REJECTION->value => [
                        'from' => AuctionWorkflowEnum::STATE_MOD_REVIEW_REJECTED->value,
                        'to' => AuctionWorkflowEnum::STATE_ACCEPTING_OFFERS->value,
                    ],
                    AuctionWorkflowEnum::TRANSITION_TO_ACCEPTING_OFFERS->value => [
                        'from' => AuctionWorkflowEnum::STATE_PENDING_MOD_REVIEW->value,
                        'to' => AuctionWorkflowEnum::STATE_ACCEPTING_OFFERS->value,
                    ],
                    AuctionWorkflowEnum::TRANSITION_TO_PENDING_OFFER_ACCEPTANCE->value => [
                        'from' => AuctionWorkflowEnum::STATE_ACCEPTING_OFFERS->value,
                        'to' => AuctionWorkflowEnum::STATE_PENDING_OFFER_ACCEPTANCE->value,
                    ],
                    AuctionWorkflowEnum::TRANSITION_TO_PENDING_BUYER_INSPECTION->value => [
                        'from' => AuctionWorkflowEnum::STATE_PENDING_OFFER_ACCEPTANCE->value,
                        'to' => AuctionWorkflowEnum::STATE_PENDING_BUYER_INSPECTION->value,
                    ],
                    AuctionWorkflowEnum::TRANSITION_TO_BUYER_INSPECTION_REJECTED->value => [
                        'from' => AuctionWorkflowEnum::STATE_PENDING_BUYER_INSPECTION->value,
                        'to' => AuctionWorkflowEnum::STATE_BUYER_INSPECTION_REJECTED->value,
                    ],
                    AuctionWorkflowEnum::TRANSITION_TO_PENDING_OFFER_ACCEPTANCE_AFTER_REJECTION->value => [
                        'from' => AuctionWorkflowEnum::STATE_BUYER_INSPECTION_REJECTED->value,
                        'to' => AuctionWorkflowEnum::STATE_PENDING_OFFER_ACCEPTANCE->value,
                    ],
                    AuctionWorkflowEnum::TRANSITION_TO_COMPLETE->value => [
                        'from' => AuctionWorkflowEnum::STATE_PENDING_BUYER_INSPECTION->value,
                        'to' => AuctionWorkflowEnum::STATE_COMPLETE->value,
                    ],
                ],
            ],
        ],
    ]);
};
