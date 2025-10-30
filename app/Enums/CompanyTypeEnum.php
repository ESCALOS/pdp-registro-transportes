<?php

namespace App\Enums;

enum CompanyTypeEnum: string
{
    case NATURAL = 'natural';
    case JURIDICA = 'juridica';

    public function label(): string
    {
        return match ($this) {
            self::NATURAL => 'Natural',
            self::JURIDICA => 'Jurídica',
        };
    }
}
