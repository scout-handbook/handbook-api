<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\LockedException;

class LockedExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\LockedException::__construct()
     */
    public function testCtor() : LockedException
    {
        $e = new LockedException('Eholder');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\LockedException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\LockedException::handle()
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