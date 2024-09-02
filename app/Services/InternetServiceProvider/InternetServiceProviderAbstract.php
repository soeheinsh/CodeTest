<?php

namespace App\Services\InternetServiceProvider;

abstract class InternetServiceProviderAbstract implements InternetServiceProviderInterface
{
    protected string $operator;
    protected int $month;
    protected int $monthlyFees;

    public function __construct()
    {
        if (!isset($this->operator)) {
            throw new \LogicException(get_class($this) . ' must have a $operator property.');
        }

        if (!isset($this->monthlyFees)) {
            throw new \LogicException(get_class($this) . ' must have a $monthlyFees property.');
        }

        $this->month = 0;
    }

    public function setMonth(int $month)
    {
        $this->month = $month;
    }

    public function calculateTotalAmount(): float|int
    {
        return $this->month * $this->monthlyFees;
    }
}
