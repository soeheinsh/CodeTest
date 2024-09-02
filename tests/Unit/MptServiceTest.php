<?php

namespace Tests\Unit;

use App\Services\InternetServiceProvider\Mpt;
use PHPUnit\Framework\TestCase;

class MptServiceTest extends TestCase
{   /** @test */
    public function it_calculates_total_amount_correctly()
    {
        $mpt = new Mpt();
        $mpt->setMonth(3); 

        $expectedAmount = 3 * 200; 
        $this->assertEquals($expectedAmount, $mpt->calculateTotalAmount());
    }
}
