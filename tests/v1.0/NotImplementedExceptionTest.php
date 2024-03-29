<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\NotImplementedException;

#[CoversClass(NotImplementedException::class)]
class NotImplementedExceptionTest extends TestCase
{
    public function testCtor(): NotImplementedException
    {
        $e = new NotImplementedException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\NotImplementedException', $e);
        return $e;
    }

    #[Depends("testCtor")]
    public function testHandle(NotImplementedException $e): void
    {
        $this->assertSame(
            [
                'status' => 501,
                'type' => 'NotImplementedException',
                'message' => 'The requested feature has not been implemented.'
            ],
            $e->handle()
        );
    }
}
