<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Group;

class GroupTest extends TestCase
{
    public function testCtor() : Group
    {
        $group = new Group('gname', 123);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Group', $group);
        return $group;
    }

    /**
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
