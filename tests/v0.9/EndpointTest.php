<?php declare(strict_types=1);
namespace v0_9;

require_once('tests/PhpInputStream.php');

global $CONFIG;

use PHPUnit\Framework\TestCase;
use TestUtils\PhpInputStream;

use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Role;
use Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
class EndpointTest extends TestCase
{
    /**
     */
    public function testCtor() : Endpoint
    {
        $endpoint = new Endpoint();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Endpoint', $endpoint);
        return $endpoint;
    }

    /**
     * @depends testCtor
     */
    public function testSetListMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setListMethod(new Role('superuser'), function () {
            return 'list';
        }));
        return $endpoint;
    }

    /**
     * @depends testSetListMethod
     */
    public function testSetGetMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setGetMethod(new Role('administrator'), function () {
            return 'get';
        }));
        return $endpoint;
    }

    /**
     * @depends testSetGetMethod
     */
    public function testSetUpdateMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setUpdateMethod(new Role('editor'), function () {
            return 'update';
        }));
        return $endpoint;
    }

    /**
     * @depends testSetUpdateMethod
     */
    public function testSetAddMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setAddMethod(new Role('user'), function () {
            return 'add';
        }));
        return $endpoint;
    }

    /**
     * @depends testSetAddMethod
     */
    public function testSetDeleteMethod(Endpoint $endpoint) : Endpoint
    {
        $this->assertNull($endpoint->setDeleteMethod(new Role('guest'), function () {
            return 'delete';
        }));
        return $endpoint;
    }

    /**
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
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperPutNoId(Endpoint $endpoint) : void
    {
        $this->expectException(MissingArgumentException::class);
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['PUT', []]);
    }

    /**
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
     * @depends testSetDeleteMethod
     */
    public function testCallFunctionHelperDeleteNoId(Endpoint $endpoint) : void
    {
        $this->expectException(MissingArgumentException::class);
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'callFunctionHelper');
        $method->setAccessible(true);
        $fn = $method->invokeArgs($endpoint, ['DELETE', []]);
    }

    /**
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
     * @depends testCtor
     */
    public function testHandleDataHelperPut(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['PUT']);
        PhpInputStream::unregister();
        $this->assertSame(['key' => 'ival'], $data);
    }

    /**
     * @depends testCtor
     */
    public function testHandleDataHelperPost(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['POST']);
        PhpInputStream::unregister();
        $this->assertSame(['key' => 'pval'], $data);
    }

    /**
     * @depends testCtor
     */
    public function testHandleDataHelperGet(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['GET']);
        PhpInputStream::unregister();
        $this->assertSame(['key' => 'gval'], $data);
    }

    /**
     * @depends testCtor
     */
    public function testHandleDataHelperDelete(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        $_GET['key'] = 'gval';
        $_POST['key'] = 'pval';
        PhpInputStream::register(['key' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['DELETE']);
        PhpInputStream::unregister();
        $this->assertSame(['key' => 'gval'], $data);
    }

    /**
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
     * @depends testCtor
     */
    public function testHandleDataHelperNoIdOverride(Endpoint $endpoint) : void
    {
        $method = new \ReflectionMethod('\Skaut\HandbookAPI\v0_9\Endpoint', 'handleDataHelper');
        $method->setAccessible(true);
        PhpInputStream::register(['id' => 'ival']);
        $data = $method->invokeArgs($endpoint, ['POST']);
        PhpInputStream::unregister();
        $this->assertSame([], $data);
    }
}
