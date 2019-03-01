<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use Skaut\HandbookAPI\v0_9\Exception\NotLockedException;

class NotLockedExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\NotLockedException::__construct()
     */
    public function testCtor() : NotLockedException
    {
        $e = new NotLockedException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\NotLockedException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\NotLockedException::handle()
     * @depends testCtor
     */
    public function testHandle(NotLockedException $e) : void
    {
        $this->assertSame(
            [
                'status' => 412,
                'type' => 'NotLockedException',
                'message' => 'This resource must be locked by a mutex for this operation.'
            ],
            $e->handle()
        );
    }
}
