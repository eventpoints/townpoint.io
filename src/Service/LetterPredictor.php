<?php

declare(strict_types = 1);

namespace App\Service;

use Phpml\Association\Apriori;

class LetterPredictor
{
    public function predict(): mixed
    {
        $associate = new Apriori(support: 0.5, confidence: 0.5);
        $samples = [['alpha', 'beta', 'epsilon'],
            ['alpha', 'beta', 'theta'],
            ['alpha', 'beta', 'epsilon'],
            ['alpha', 'beta', 'theta'],
        ];
        $labels = [];
        $associate->train($samples, $labels);

        return $associate->predict(['']);
    }
}
