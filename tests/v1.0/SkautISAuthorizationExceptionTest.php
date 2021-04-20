<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\SkautISAuthorizationException;

class SkautISAuthorizationExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\SkautISAuthorizationException::__construct()
     */
    public function testCtor() : SkautISAuthorizationException
    {
        $e = new SkautISAuthorizationException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\SkautISAuthorizationException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\SkautISAuthorizationException::handle()
     * @depends testCtor
     */
    public function testHandle(SkautISAuthorizationException $e) : void
    {
        $this->assertSame(
            [
                'status' => 403,
                'type' => 'SkautISAuthorizationException',
                'message' => 'Insufficient SkautIS authorization'
            ],
            $e->handle()
        );
    }
}
