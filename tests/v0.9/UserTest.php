<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\User;

class UserTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\User::__construct()
     */
    public function testCtor() : User
    {
        $user = new User(12345, 'uname', 'superuser');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\User', $user);
        return $user;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\User::jsonSerialize
     * @depends testCtor
     */
    public function testJsonSerializeNoGroups(User $user) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":12345,"name":"uname","role":"superuser","groups":[]}',
            json_encode($user)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\User::jsonSerialize
     */
    public function testJsonSerializeGroups() : void
    {
        $user = new User(12345, 'uname', 'superuser');
        $user->addGroup(pack('H*', '1739a63aa2544a959508103b7c80bcdb'));
        $this->assertJsonStringEqualsJsonString(
            '{"id":12345,"name":"uname","role":"superuser","groups":["1739a63a-a254-4a95-9508-103b7c80bcdb"]}',
            json_encode($user)
        );
    }
}
