<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\NotImplementedException;

class NotImplementedExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\NotImplementedException::__construct()
     */
    public function testCtor() : NotImplementedException
    {
        $e = new NotImplementedException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\NotImplementedException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\NotImplementedException::handle()
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
