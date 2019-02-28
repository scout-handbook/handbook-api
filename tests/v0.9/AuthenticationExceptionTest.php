<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use Skaut\HandbookAPI\v0_9\Exception\AuthenticationException;

class AuthenticationExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\AuthenticationException::__construct()
     */
    public function testCtor() : AuthenticationException
    {
        $e = new AuthenticationException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\AuthenticationException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\AuthenticationException::handle()
     * @depends testCtor
     */
    public function testHandle(AuthenticationException $e) : void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'AuthenticationException', 'message' => 'Authentication failed.'],
            $e->handle()
        );
    }
}
