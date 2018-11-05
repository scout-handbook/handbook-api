<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/MissingArgumentException.php');

class MissingArgumentExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\MissingArgumentException::__construct()
     */
    public function testCtorGet() : \HandbookAPI\MissingArgumentException
    {
        $e = new \HandbookAPI\MissingArgumentException(\HandbookAPI\MissingArgumentException::GET, 'Gname');
        $this->assertInstanceOf('\HandbookAPI\MissingArgumentException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\MissingArgumentException::__construct()
     */
    public function testCtorPost() : \HandbookAPI\MissingArgumentException
    {
        $e = new \HandbookAPI\MissingArgumentException(\HandbookAPI\MissingArgumentException::POST, 'Pname');
        $this->assertInstanceOf('\HandbookAPI\MissingArgumentException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\MissingArgumentException::__construct()
     */
    public function testCtorFile() : \HandbookAPI\MissingArgumentException
    {
        $e = new \HandbookAPI\MissingArgumentException(\HandbookAPI\MissingArgumentException::FILE, 'Fname');
        $this->assertInstanceOf('\HandbookAPI\MissingArgumentException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\MissingArgumentException::handle()
     * @depends testCtorGet
     */
    public function testHandleGet(\HandbookAPI\MissingArgumentException $e) : void
    {
        $this->assertSame(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'GET argument "Gname" must be provided.'
            ],
            $e->handle()
        );
    }

    /**
     * @covers HandbookAPI\MissingArgumentException::handle()
     * @depends testCtorPost
     */
    public function testHandlePost(\HandbookAPI\MissingArgumentException $e) : void
    {
        $this->assertSame(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'POST argument "Pname" must be provided.'
            ],
            $e->handle()
        );
    }

    /**
     * @covers HandbookAPI\MissingArgumentException::handle()
     * @depends testCtorFile
     */
    public function testHandleFile(\HandbookAPI\MissingArgumentException $e) : void
    {
        $this->assertSame(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'FILE argument "Fname" must be provided.'
            ],
            $e->handle()
        );
    }
}
