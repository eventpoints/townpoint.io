<?php

declare(strict_types = 1);

use App\Entity\Auction\Auction;
use App\Entity\User;
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
                'supports' => [Auction::class],
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
        ],
    ]);
};
