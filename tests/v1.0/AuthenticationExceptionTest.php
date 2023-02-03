<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\AuthenticationException;

class AuthenticationExceptionTest extends TestCase
{
    public function testCtor() : AuthenticationException
    {
        $e = new AuthenticationException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\AuthenticationException', $e);
        return $e;
    }

    /**
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
