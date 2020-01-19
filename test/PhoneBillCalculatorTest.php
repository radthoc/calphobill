<?php

namespace test;

use Calphobill\Services\PhoneBillCalculator;
use PHPUnit\Framework\TestCase;

class PhoneBillCalculatorTest extends TestCase
{

    /** @var PhoneBillCalculator */
    private $PhoneBillCalculator;

    public function setUp()
    {
        $this->PhoneBillCalculator = new PhoneBillCalculator();
    }

    public function testCalculatePhoneBill()
    {
        $billStr = "00:01:07,400-234-090
   00:05:01,701-080-080
   00:05:00,400-234-090";

        $billAmount = $this->PhoneBillCalculator->calculatePhoneBill($billStr);

        $this->assertEquals(900, $billAmount);
    }
}