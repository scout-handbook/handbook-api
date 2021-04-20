<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\NotLockedException;

class NotLockedExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\NotLockedException::__construct()
     */
    public function testCtor() : NotLockedException
    {
        $e = new NotLockedException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\NotLockedException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\NotLockedException::handle()
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
