<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Exception\NotLockedException;

#[CoversClass(NotLockedException::class)]
class NotLockedExceptionTest extends TestCase
{
    public function test_ctor(): NotLockedException
    {
        $e = new NotLockedException;
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\NotLockedException', $e);

        return $e;
    }

    #[Depends('test_ctor')]
    public function test_handle(NotLockedException $e): void
    {
        $this->assertSame(
            [
                'status' => 412,
                'type' => 'NotLockedException',
                'message' => 'This resource must be locked by a mutex for this operation.',
            ],
            $e->handle()
        );
    }
}
