<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Exception\ConnectionException;

#[CoversClass(ConnectionException::class)]
class ConnectionExceptionTest extends TestCase
{
    public function test_ctor(): ConnectionException
    {
        $e = new ConnectionException(new \PDOException);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\ConnectionException', $e);

        return $e;
    }

    #[Depends('test_ctor')]
    public function test_handle(ConnectionException $e): void
    {
        $this->assertSame(
            [
                'status' => 500,
                'type' => 'ConnectionException',
                'message' => 'Database connection request failed. Error message: "".',
            ],
            $e->handle()
        );
    }
}
