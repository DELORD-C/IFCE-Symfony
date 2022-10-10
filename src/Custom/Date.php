<?php

namespace App\Custom;

class Date
{
    function today (): string
    {
        return date('d-m-y');
    }
}