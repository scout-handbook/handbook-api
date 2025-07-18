<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Exception\InvalidArgumentTypeException;

#[CoversClass(InvalidArgumentTypeException::class)]
class InvalidArgumentTypeExceptionTest extends TestCase
{
    public function test_ctor(): InvalidArgumentTypeException
    {
        $e = new InvalidArgumentTypeException('Ename', ['json', 'int']);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\InvalidArgumentTypeException', $e);

        return $e;
    }

    #[Depends('test_ctor')]
    public function test_handle(InvalidArgumentTypeException $e): void
    {
        $this->assertSame(
            [
                'status' => 415,
                'type' => 'InvalidArgumentTypeException',
                'message' => 'Argument "Ename" must be of type json, int.',
            ],
            $e->handle()
        );
    }
}
