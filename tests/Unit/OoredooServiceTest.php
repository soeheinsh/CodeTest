<?php

namespace Tests\Unit;

use App\Services\InternetServiceProvider\Ooredoo;
use PHPUnit\Framework\TestCase;

class OoredooServiceTest extends TestCase
{
    /** @test */
    public function it_calculates_total_amount_correctly()
    {
        $ooredoo = new Ooredoo();
        $ooredoo->setMonth(2); 

        $expectedAmount = 2 * 150; 
        $this->assertEquals($expectedAmount, $ooredoo->calculateTotalAmount());
    }
}
