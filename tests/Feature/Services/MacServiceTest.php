<?php

namespace Tests\Feature\Services;

use App\Services\MacService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MacServiceTest extends TestCase
{
    #[Test]
    public function macservice_clean_function_returns_null_for_null_input(): void
    {
        $macService = new MacService;

        $this->assertNull($macService->clean());
    }

    #[Test]
    public function macservice_clean_function_returns_clean_mac_address_for_proper_input(): void
    {
        $macService = new MacService;

        $this->assertEquals('12:34:56:78:90:AB', $macService->clean('12:34:56:78:90:ab'));
        $this->assertEquals('12:34:56:78:90:AB', $macService->clean('12-34-56-78-90-ab'));
        $this->assertEquals('12:34:56:78:90:AB', $macService->clean('1234.5678.90ab'));
    }

    #[Test]
    public function macservice_isreserved_function_works_correctly(): void
    {
        $macService = new MacService;

        $this->assertTrue($macService->isReserved('01005e000000'));
        $this->assertTrue($macService->isReserved('00005e000100'));
        $this->assertTrue($macService->isReserved('00005e000200'));
        $this->assertTrue($macService->isReserved('333300000000'));
        $this->assertTrue($macService->isReserved('cf0000000000'));
        $this->assertTrue($macService->isReserved('00005e000000'));
    }
}
