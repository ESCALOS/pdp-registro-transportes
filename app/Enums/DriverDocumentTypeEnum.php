<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum DriverDocumentTypeEnum: int implements HasColor, HasIcon, HasLabel
{
    case DNI = 1; // Documento Nacional de Identidad
    case CE = 2; // Carné de Extranjería

    public function getLabel(): string
    {
        return match ($this) {
            self::DNI => 'DNI',
            self::CE => 'Carné de Extranjería',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DNI => 'primary',
            self::CE => 'secondary',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::DNI => 'heroicon-o-identification',
            self::CE => 'heroicon-o-passport',
        };
    }
}
