<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/ExecutionException.php');

class ExecutionExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\ExecutionException::__construct()
     */
    public function testCtor() : \HandbookAPI\ExecutionException
    {
        $e = new \HandbookAPI\ExecutionException('EXAMPLE QUERY', new \PDOStatement());
        $this->assertInstanceOf('\HandbookAPI\ExecutionException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\ExecutionException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\ExecutionException $e) : void
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
