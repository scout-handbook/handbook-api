<?php declare(strict_types=1);
namespace v0_9;

use PHPUnit\Framework\TestCase;

global $CONFIG;
require_once('v0.9/internal/Group.php');

class GroupTest extends TestCase
{
    /**
     * @covers HandbookAPI\Group::__construct()
     */
    public function testCtor() : \HandbookAPI\Group
    {
        $group = new \HandbookAPI\Group(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'gname', 123);
        $this->assertInstanceOf('\HandbookAPI\Group', $group);
        return $group;
    }

    /**
     * @covers HandbookAPI\Group::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerialize(\HandbookAPI\Group $group) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"gname","count":123}',
            json_encode($group)
        );
    }

    /**
     * @covers HandbookAPI\Group::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalid() : void // TODO: Specialize exception type
    {
        new \HandbookAPI\Group(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'gname', 123);
    }
}
