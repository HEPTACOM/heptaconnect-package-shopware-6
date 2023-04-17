<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Support;

abstract class LetterCase
{
    /**
     * Convert texts like `sales-channel` to `sales_channel`.
     */
    public static function fromDashToUnderscore(string $text): string
    {
        return \str_replace('-', '_', \strtolower($text));
    }

    /**
     * Convert texts like `sales_channel` to `sales-channel`.
     */
    public static function fromUnderscoreToDash(string $text): string
    {
        return \str_replace('_', '-', \strtolower($text));
    }
}
