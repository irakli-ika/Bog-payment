<?php

if (! function_exists('price_to_cent')) {
    function price_to_cent(float $amount): int
    {
        return $amount * 100;
    }
}

if (! function_exists('price_from_cent')) {
    function price_from_cent(int $amount): float
    {
        return $amount / 100;
    }
}

if (! function_exists('mask_card_number')) {
    function mask_card_number(string $number): string
    {
        return '**** **** **** '.substr($number, -4);
    }
}
