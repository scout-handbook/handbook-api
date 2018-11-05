<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/LockedException.php');

class LockedExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\LockedException::__construct()
     */
    public function testCtor() : \HandbookAPI\LockedException
    {
        $e = new \HandbookAPI\LockedException('Eholder');
        $this->assertInstanceOf('\HandbookAPI\LockedException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\LockedException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\LockedException $e) : void
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
