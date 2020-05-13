<?php

namespace App\Enums;

use Spatie\Enum\Enum;

class Gender extends Enum
{
    public static function MALE(): Gender
    {
        return new class() extends Gender {
            public function getValue(): string
            {
                return 'M';
            }
        };
    }

    public static function FEMALE(): Gender
    {
        return new class() extends Gender {
            public function getValue(): string
            {
                return 'F';
            }
        };
    }
}
