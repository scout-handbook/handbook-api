<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\InvalidArgumentTypeException;

class InvalidArgumentTypeExceptionTest extends TestCase
{
    public function testCtor() : InvalidArgumentTypeException
    {
        $e = new InvalidArgumentTypeException('Ename', ['json', 'int']);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\InvalidArgumentTypeException', $e);
        return $e;
    }

    /**
     * @depends testCtor
     */
    public function testHandle(InvalidArgumentTypeException $e) : void
    {
        $this->assertSame(
            [
                'status' => 415,
                'type' => 'InvalidArgumentTypeException',
                'message' => 'Argument "Ename" must be of type json, int.'
            ],
            $e->handle()
        );
    }
}
