<?php

namespace App\Services\InternetServiceProvider;

class Ooredoo extends InternetServiceProviderAbstract
{
    protected string $operator = 'ooredoo';
    protected int $monthlyFees = 150;
}
