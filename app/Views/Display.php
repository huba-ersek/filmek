<?php

namespace App\Views;

class Display
{
    static function message(string $message, string $_type = 'text', $_important = false): void
    {
        echo "<div>" . $message . "</div>";
    }
}