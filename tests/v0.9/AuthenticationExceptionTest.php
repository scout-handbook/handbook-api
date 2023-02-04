<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\AuthenticationException;

#[CoversClass(AuthenticationException::class)]
class AuthenticationExceptionTest extends TestCase
{
    public function testCtor() : AuthenticationException
    {
        $e = new AuthenticationException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\AuthenticationException', $e);
        return $e;
    }

    #[Depends("testCtor")]
    public function testHandle(AuthenticationException $e) : void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'AuthenticationException', 'message' => 'Authentication failed.'],
            $e->handle()
        );
    }
}
