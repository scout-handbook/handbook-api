<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/NotFoundException.php');

class NotFoundExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\NotFoundException::__construct()
     */
    public function testCtor() : \HandbookAPI\NotFoundException
    {
        $e = new \HandbookAPI\NotFoundException('Rname');
        $this->assertInstanceOf('\HandbookAPI\NotFoundException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\NotFoundException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\NotFoundException $e) : void
    {
        $this->assertSame(
            ['status' => 404, 'type' => 'NotFoundException', 'message' => 'No such Rname has been found.'],
            $e->handle()
        );
    }
}
