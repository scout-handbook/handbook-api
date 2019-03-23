<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\ExecutionException;

class ExecutionExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\ExecutionException::__construct()
     */
    public function testCtor() : ExecutionException
    {
        $e = new ExecutionException('EXAMPLE QUERY', new \PDOStatement());
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\ExecutionException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\ExecutionException::handle()
     * @depends testCtor
     */
    public function testHandle(ExecutionException $e) : void
    {
        $this->assertSame(
            [
                'status' => 500,
                'type' => 'ExecutionException',
                'message' => 'Query "EXAMPLE QUERY" has failed. Error message: "".'
            ],
            $e->handle()
        );
    }
}
