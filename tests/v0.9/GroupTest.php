<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Group;

#[CoversClass(Group::class)]
class GroupTest extends TestCase
{
    public function testCtor() : Group
    {
        $group = new Group(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'gname', 123);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Group', $group);
        return $group;
    }

    /**
     * @depends testCtor
     */
    public function testJsonSerialize(Group $group) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"gname","count":123}',
            json_encode($group)
        );
    }

    public function testCtorInvalid() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Group(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'gname', 123);
    }
}
