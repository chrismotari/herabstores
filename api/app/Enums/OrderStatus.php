<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * new → dispatched → delivered; new/dispatched → canceled.
 */
enum OrderStatus: string implements HasColor, HasLabel
{
    case New = 'new';
    case Dispatched = 'dispatched';
    case Delivered = 'delivered';
    case Canceled = 'canceled';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'info',
            self::Dispatched => 'warning',
            self::Delivered => 'success',
            self::Canceled => 'gray',
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return match ($this) {
            self::New => in_array($next, [self::Dispatched, self::Canceled], true),
            self::Dispatched => in_array($next, [self::Delivered, self::Canceled], true),
            self::Delivered, self::Canceled => false,
        };
    }
}
