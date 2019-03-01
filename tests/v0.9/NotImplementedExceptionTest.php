<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use Skaut\HandbookAPI\v0_9\Exception\NotImplementedException;

class NotImplementedExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\NotImplementedException::__construct()
     */
    public function testCtor() : NotImplementedException
    {
        $e = new NotImplementedException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\NotImplementedException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\NotImplementedException::handle()
     * @depends testCtor
     */
    public function testHandle(NotImplementedException $e) : void
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
