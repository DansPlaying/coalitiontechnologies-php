<?php

namespace App\Enums;

enum Priority: int
{
    case UltraHigh = 1;
    case High      = 2;
    case Medium    = 3;
    case Low       = 4;

    public function label(): string
    {
        return match($this) {
            Priority::UltraHigh => 'Ultra High',
            Priority::High      => 'High',
            Priority::Medium    => 'Medium',
            Priority::Low       => 'Low',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            Priority::UltraHigh => 'bg-red-600 text-white ring-red-700',
            Priority::High      => 'bg-red-200 text-red-700 ring-red-300',
            Priority::Medium    => 'bg-yellow-100 text-yellow-700 ring-yellow-300',
            Priority::Low       => 'bg-blue-100 text-blue-700 ring-blue-200',
        };
    }
}
