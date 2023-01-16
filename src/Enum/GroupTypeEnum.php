<?php

declare(strict_types = 1);

namespace App\Enum;

enum GroupTypeEnum: string
{
    case recreational = 'recreational';
    case professional = 'professional';
    case union = 'union';
    case political = 'political';
    case accommodation = 'accommodation';
    case activism = 'activism';
    case religion = 'religion';
    case charity = 'charity';

    /**
     * @return array{recreational: string, professional: string, union: string, political: string, accommodation: string, activism: string, religion: string, charity: string}
     */
    public static function getArrayCases(): array
    {
        return [
            self::recreational->name => self::recreational->value,
            self::professional->name => self::professional->value,
            self::union->name => self::union->value,
            self::political->name => self::political->value,
            self::accommodation->name => self::accommodation->value,
            self::activism->name => self::activism->value,
            self::religion->name => self::religion->value,
            self::charity->name => self::charity->value,
        ];
    }
}
