<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\RefusedException;

class RefusedExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\RefusedException::__construct()
     */
    public function testCtor() : RefusedException
    {
        $e = new RefusedException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\RefusedException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\RefusedException::handle()
     * @depends testCtor
     */
    public function testHandle(RefusedException $e) : void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'RefusedException', 'message' => 'Operation has been refused by the server.'],
            $e->handle()
        );
    }
}
