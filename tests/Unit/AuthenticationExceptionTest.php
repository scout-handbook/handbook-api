<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Exception\AuthenticationException;

#[CoversClass(AuthenticationException::class)]
class AuthenticationExceptionTest extends TestCase
{
    public function test_ctor(): AuthenticationException
    {
        $e = new AuthenticationException;
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\AuthenticationException', $e);

        return $e;
    }

    #[Depends('test_ctor')]
    public function test_handle(AuthenticationException $e): void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'AuthenticationException', 'message' => 'Authentication failed.'],
            $e->handle()
        );
    }
}
