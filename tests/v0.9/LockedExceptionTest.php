<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\LockedException;

#[CoversClass(LockedException::class)]
class LockedExceptionTest extends TestCase
{
    public function testCtor() : LockedException
    {
        $e = new LockedException('Eholder');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\LockedException', $e);
        return $e;
    }

    /**
     * @depends testCtor
     */
    public function testHandle(LockedException $e) : void
    {
        $this->assertSame(
            [
                'status' => 409,
                'type' => 'LockedException',
                'message' => 'This resource is currently locked by a different user.',
                'holder' => 'Eholder'
            ],
            $e->handle()
        );
    }
}
