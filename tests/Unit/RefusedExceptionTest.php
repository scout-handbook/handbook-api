<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Exception\RefusedException;

#[CoversClass(RefusedException::class)]
class RefusedExceptionTest extends TestCase
{
    public function test_ctor(): RefusedException
    {
        $e = new RefusedException;
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\RefusedException', $e);

        return $e;
    }

    #[Depends('test_ctor')]
    public function test_handle(RefusedException $e): void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'RefusedException', 'message' => 'Operation has been refused by the server.'],
            $e->handle()
        );
    }
}
