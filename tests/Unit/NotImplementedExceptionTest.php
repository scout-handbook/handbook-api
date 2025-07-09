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
    public function test_ctor(): NotImplementedException
    {
        $e = new NotImplementedException;
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\NotImplementedException', $e);

        return $e;
    }

    #[Depends('test_ctor')]
    public function test_handle(NotImplementedException $e): void
    {
        $this->assertSame(
            [
                'status' => 501,
                'type' => 'NotImplementedException',
                'message' => 'The requested feature has not been implemented.',
            ],
            $e->handle()
        );
    }
}
