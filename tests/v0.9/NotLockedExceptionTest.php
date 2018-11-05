<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/NotLockedException.php');

class NotLockedExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\NotLockedException::__construct()
     */
    public function testCtor() : \HandbookAPI\NotLockedException
    {
        $e = new \HandbookAPI\NotLockedException();
        $this->assertInstanceOf('\HandbookAPI\NotLockedException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\NotLockedException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\NotLockedException $e) : void
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
