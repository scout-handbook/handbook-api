<?php declare(strict_types=1);
namespace v0_9;

require_once('tests/PhpInputStream.php');

global $CONFIG;
require_once('v0.9/internal/Endpoint.php');

class EndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\Endpoint::__construct()
     */
    public function testCtor() : \HandbookAPI\Endpoint
    {
        $endpoint = new \HandbookAPI\Endpoint();
        $this->assertInstanceOf('\HandbookAPI\Endpoint', $endpoint);
        return $endpoint;
    }

    /**
     * @covers HandbookAPI\Endpoint::setListMethod()
     * @depends testCtor
     */
    public function testSetListMethod(\HandbookAPI\Endpoint $endpoint) : \HandbookAPI\Endpoint
    {
        $this->assertNull($endpoint->setListMethod(new \HandbookAPI\Role('superuser'), function () {
            return 'list';
        }));
        return $endpoint;
    }

    /**
     * @covers HandbookAPI\Endpoint::setGetMethod()
     * @depends testSetListMethod
     */
    public function testSetGetMethod(\HandbookAPI\Endpoint $endpoint) : \HandbookAPI\Endpoint
    {
        $this->assertNull($endpoint->setGetMethod(new \HandbookAPI\Role('administrator'), function () {
            return 'get';
        }));
        return $endpoint;
    }

    /**
     * @covers HandbookAPI\Endpoint::setUpdateMethod()
     * @depends testSetGetMethod
     */
    public function testSetUpdateMethod(\HandbookAPI\Endpoint $endpoint) : \HandbookAPI\Endpoint
    {
        $this->assertNull($endpoint->setUpdateMethod(new \HandbookAPI\Role('editor'), function () {
            return 'update';
        }));
        return $endpoint;
    }

    /**
     * @covers HandbookAPI\Endpoint::setAddMethod()
     * @depends testSetUpdateMethod
     */
    public function testSetAddMethod(\HandbookAPI\Endpoint $endpoint) : \HandbookAPI\Endpoint
    {
        $this->assertNull($endpoint->setAddMethod(new \HandbookAPI\Role('user'), function () {
            return 'add';
        }));
        return $endpoint;
    }

    /**
     * @covers HandbookAPI\Endpoint::setDeleteMethod()
     * @depends testSetAddMethod
     */
    public function testSetDeleteMethod(\HandbookAPI\Endpoint $endpoint) : \HandbookAPI\Endpoint
    {
        $this->assertNull($endpoint->setDeleteMethod(new \HandbookAPI\Role('guest'), function () {
            return 'delete';
        }));
        return $endpoint;
    }

    /**
     * @covers HandbookAPI\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperPutId(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['PUT', ['id' => '78a8aacf-402f-4643-a5f8-2bce404e6353']]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('update', $fn());
    }

    /**
     * @covers HandbookAPI\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperPostId(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['POST', []]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('add', $fn());
    }

    /**
     * @covers HandbookAPI\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperDeleteId(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['DELETE', ['id' => '78a8aacf-402f-4643-a5f8-2bce404e6353']]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('delete', $fn());
    }

    /**
     * @covers HandbookAPI\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperGetId(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['GET', ['id' => '78a8aacf-402f-4643-a5f8-2bce404e6353']]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('get', $fn());
    }

    /**
     * @covers HandbookAPI\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     * @expectedException HandbookAPI\MissingArgumentException
     */
    public function testCallFunctionHelperPutNoId(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['PUT', []]);
    }

    /**
     * @covers HandbookAPI\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperPostNoId(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['POST', []]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('add', $fn());
    }

    /**
     * @covers HandbookAPI\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     * @expectedException HandbookAPI\MissingArgumentException
     */
    public function testCallFunctionHelperDeleteNoId(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['DELETE', []]);
    }

    /**
     * @covers HandbookAPI\Endpoint::callFunctionHelper
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperGetNoId(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['GET', []]);
        $this->assertTrue(is_callable($fn));
        $this->assertSame('list', $fn());
    }

    /**
     * @covers HandbookAPI\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperPut(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        \TestUtils\PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['PUT']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame(['key' => 'ival'], $data);
    }

    /**
     * @covers HandbookAPI\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperPost(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        \TestUtils\PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['POST']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame(['key' => 'pval'], $data);
    }

    /**
     * @covers HandbookAPI\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperGet(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        \TestUtils\PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['GET']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame(['key' => 'gval'], $data);
    }

    /**
     * @covers HandbookAPI\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperDelete(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        \TestUtils\PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['DELETE']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame(['key' => 'gval'], $data);
    }

    /**
     * @covers HandbookAPI\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperGetIdOverride(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['id'] = 'gval';
        $_POST['id'] = 'pval';
        $data = $method->invokeArgs($endpoint, ['POST']);
        $this->assertSame(['id' => 'gval'], $data);
    }

    /**
     * @covers HandbookAPI\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperGetIdNoOverride(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['id'] = '';
        $_POST['id'] = 'pval';
        $data = $method->invokeArgs($endpoint, ['POST']);
        $this->assertSame(['id' => 'pval'], $data);
    }

    /**
     * @covers HandbookAPI\Endpoint::handleDataHelper
     * @depends testCtor
     */
    public function testHandleDataHelperNoIdOverride(\HandbookAPI\Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\HandbookAPI\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        \TestUtils\PhpInputStream::register(['id' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['POST']);
        \TestUtils\PhpInputStream::unregister();
        $this->assertSame([], $data);
    }
}
