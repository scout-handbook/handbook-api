<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Group;

class GroupTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Group::__construct()
     */
    public function testCtor() : Group
    {
        $group = new Group(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'gname', 123);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Group', $group);
        return $group;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Group::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerialize(Group $group) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"gname","count":123}',
            json_encode($group)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Group::__construct()
     */
    public function testCtorInvalid() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Group(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'gname', 123);
    }
}
