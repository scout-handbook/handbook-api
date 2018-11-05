<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/InvalidArgumentTypeException.php');

class InvalidArgumentTypeExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\InvalidArgumentTypeException::__construct()
     */
    public function testCtor() : \HandbookAPI\InvalidArgumentTypeException
    {
        $e = new \HandbookAPI\InvalidArgumentTypeException('Ename', ['json', 'int']);
        $this->assertInstanceOf('\HandbookAPI\InvalidArgumentTypeException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\InvalidArgumentTypeException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\InvalidArgumentTypeException $e) : void
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
