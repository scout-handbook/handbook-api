<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use Skaut\HandbookAPI\v0_9\Exception\NotFoundException;

class NotFoundExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\NotFoundException::__construct()
     */
    public function testCtor() : NotFoundException
    {
        $e = new NotFoundException('Rname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\NotFoundException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\NotFoundException::handle()
     * @depends testCtor
     */
    public function testHandle(NotFoundException $e) : void
    {
        $this->assertSame(
            ['status' => 404, 'type' => 'NotFoundException', 'message' => 'No such Rname has been found.'],
            $e->handle()
        );
    }
}
