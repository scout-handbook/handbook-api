<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/AuthenticationException.php');

class AuthenticationExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\AuthenticationException::__construct()
     */
    public function testCtor() : \HandbookAPI\AuthenticationException
    {
        $e = new \HandbookAPI\AuthenticationException();
        $this->assertInstanceOf('\HandbookAPI\AuthenticationException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\AuthenticationException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\AuthenticationException $e) : void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'AuthenticationException', 'message' => 'Authentication failed.'],
            $e->handle()
        );
    }
}
