<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Exception\SkautISAuthorizationException;

#[CoversClass(SkautISAuthorizationException::class)]
class SkautISAuthorizationExceptionTest extends TestCase
{
    public function test_ctor(): SkautISAuthorizationException
    {
        $e = new SkautISAuthorizationException;
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\SkautISAuthorizationException', $e);

        return $e;
    }

    #[Depends('test_ctor')]
    public function test_handle(SkautISAuthorizationException $e): void
    {
        $this->assertSame(
            [
                'status' => 403,
                'type' => 'SkautISAuthorizationException',
                'message' => 'Insufficient SkautIS authorization',
            ],
            $e->handle()
        );
    }
}
