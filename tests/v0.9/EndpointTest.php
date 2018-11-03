<?php declare(strict_types=1);
namespace v0_9;

use PHPUnit\Framework\TestCase;

global $CONFIG;
require_once('v0.9/internal/Endpoint.php');

class EndpointTest extends TestCase
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
}
