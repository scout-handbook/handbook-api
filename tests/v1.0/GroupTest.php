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
        $group = new Group('gname', 123);
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
            '{"name":"gname","count":123}',
            json_encode($group)
        );
    }
}
