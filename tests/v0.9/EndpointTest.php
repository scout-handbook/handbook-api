<?php declare(strict_types=1);
namespace v0_9;

require_once('tests/PhpInputStream.php');

global $CONFIG;

use Skaut\HandbookAPI\v0_9\Endpoint;

class EndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::__construct()
     */
    public function testCtor() : Endpoint
    {
        $endpoint = new Endpoint();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Endpoint', $endpoint);
        return $endpoint;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::setListMethod()
     * @depends testCtor
     */
    public function testSetListMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setListMethod(new \HandbookAPI\Role('superuser'), function () {
            return 'list';
        }));
        return $endpoint;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::setGetMethod()
     * @depends testSetListMethod
     */
    public function testSetGetMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setGetMethod(new \HandbookAPI\Role('administrator'), function () {
            return 'get';
        }));
        return $endpoint;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::setUpdateMethod()
     * @depends testSetGetMethod
     */
    public function testSetUpdateMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setUpdateMethod(new \HandbookAPI\Role('editor'), function () {
            return 'update';
        }));
        return $endpoint;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::setAddMethod()
     * @depends testSetUpdateMethod
     */
    public function testSetAddMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setAddMethod(new \HandbookAPI\Role('user'), function () {
            return 'add';
        }));
        return $endpoint;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::setDeleteMethod()
     * @depends testSetAddMethod
     */
    public function testSetDeleteMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setDeleteMethod(new \HandbookAPI\Role('guest'), function () {
            return 'delete';
        }));
        return $endpoint;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperPutId(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['PUT', ['id' => '78a8aacf-402f-4643-a5f8-2bce404e6353']]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('update', $fn());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperPostId(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['POST', []]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('add', $fn());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperDeleteId(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['DELETE', ['id' => '78a8aacf-402f-4643-a5f8-2bce404e6353']]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('delete', $fn());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperGetId(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['GET', ['id' => '78a8aacf-402f-4643-a5f8-2bce404e6353']]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('get', $fn());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     * @expectedException Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException
     */
    public function testCallFunctionHelperPutNoId(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['PUT', []]);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperPostNoId(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['POST', []]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('add', $fn());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     * @expectedException Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException
     */
    public function testCallFunctionHelperDeleteNoId(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['DELETE', []]);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperGetNoId(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['GET', []]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('list', $fn());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperPut(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        \TestUtils\PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['PUT']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame(['key' => 'ival'], $data);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperPost(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        \TestUtils\PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['POST']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame(['key' => 'pval'], $data);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperGet(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        \TestUtils\PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['GET']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame(['key' => 'gval'], $data);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperDelete(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        \TestUtils\PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['DELETE']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame(['key' => 'gval'], $data);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperGetIdOverride(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['id'] = 'gval';
        $_POST['id'] = 'pval';
        $data = $method->invokeArgs($endpoint, ['POST']);
        $this->assertSame(['id' => 'gval'], $data);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperGetIdNoOverride(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['id'] = '';
        $_POST['id'] = 'pval';
        $data = $method->invokeArgs($endpoint, ['POST']);
        $this->assertSame(['id' => 'pval'], $data);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperNoIdOverride(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        \TestUtils\PhpInputStream::register(['id' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['POST']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame([], $data);
    }
}
