<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException;

class MissingArgumentExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException::__construct()
     */
    public function testCtorGet() : MissingArgumentException
    {
        $e = new MissingArgumentException(MissingArgumentException::GET, 'Gname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException::__construct()
     */
    public function testCtorPost() : MissingArgumentException
    {
        $e = new MissingArgumentException(MissingArgumentException::POST, 'Pname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException::__construct()
     */
    public function testCtorFile() : MissingArgumentException
    {
        $e = new MissingArgumentException(MissingArgumentException::FILE, 'Fname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException::handle()
     * @depends testCtorGet
     */
    public function testHandleGet(MissingArgumentException $e) : void
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
     * @covers Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException::handle()
     * @depends testCtorPost
     */
    public function testHandlePost(MissingArgumentException $e) : void
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
     * @covers Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException::handle()
     * @depends testCtorFile
     */
    public function testHandleFile(MissingArgumentException $e) : void
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
